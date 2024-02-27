<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ticket;
use App\Models\Session;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Redsys\API_PHP\redsysHMAC256_API_PHP_7\RedsysAPI;

class PaymentController extends Controller
{
    public function getSessionDataForPayment(Request $request)
    {

        try {
            Log::info("Llamada al método Paymentcontroller.getSessionDataForPayment");
            $ticketTypeId = $request->input('ticketTId');
            $sessionData = Session::getSessionByTicketTypeID($ticketTypeId);

            return view('payment.index', ['sessionData' => $sessionData]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    //Función para generar arrays independientes de los asistentes e insertarlos en base de datos
    public function extractAssistantsToArray(array $assistantsArray, int $purchaseId)
    {
        try {
            Log::info('Llamada al método PaymentController.extractAssistantsToArray');

            $arrayToBD = [];
            $tempArray = [];
            if ($assistantsArray['nameAssistants'] === null) {
                for ($i = 0; $i < count($assistantsArray['ticketQuantity']); $i++) {
                    for ($j=0; $j <$assistantsArray['ticketQuantity'][$i] ; $j++) { 
                        $tempArray = [
                            "purchase_id" => $purchaseId,
                            "ticket_type_id" => $assistantsArray['ticket_id'][$i],
                            "name" => null,
                            "dni" => null,
                            "phone_number" => null
                        ];
                        array_push($arrayToBD, $tempArray);
                    }
                }
            } else {
                for ($i = 0; $i < count($assistantsArray['nameAssistants']); $i++) {
                    $tempArray = [
                        "purchase_id" => $purchaseId,
                        "ticket_type_id" => $assistantsArray['ticket_id'][$i],
                        "name" => $assistantsArray['nameAssistants'][$i],
                        "dni" => $assistantsArray['dniAssistants'][$i],
                        "phone_number" => $assistantsArray['mobileAssistants'][$i]
                    ];
                    array_push($arrayToBD, $tempArray);
                }
            }
            Log::info('extraccion exitosa');

            return ($arrayToBD);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    //Función que inserta a los asistentes en la tabla tickets
    public function insertAssistants(int $purchaseId)
    {
        try {
            Log::info('Llamada al método PaymentController.insertAssistants');
            $assistantsArray = session('assistants');
            session()->forget('assistants');
            $dataAssistantsBD = self::extractAssistantsToArray($assistantsArray, $purchaseId);
            Ticket::createRegisterPurchase($dataAssistantsBD);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    //Función para insertar al comprador de los tickets
    public function insertPurchaser()
    {
        try {
            Log::info("Llamada al método PaymentController.insertPurchase");

            $owner = session('owner');
            session()->forget('owner');
            $purchaseDataInserted = Purchase::createPurchase($owner);
            if (session('assistants') != null) {
                self::insertAssistants($purchaseDataInserted['purchase_id']);
            }
            GeneratorPDF::sendPdfEmail($purchaseDataInserted['email'], $purchaseDataInserted['session_id']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    //Función que recibe los datos de la vista "resumen-compra" organiza la información y la envia a Redsys
    public function paymentRedsys(Request $request)
    {
        try {
            Log::info("Llamada al método PaymentController.paymentRedsys");

            //Validamos la información que llega de los formularios

            $validateDataforms = $request->validate([
                'owner' => 'required|string|max:255',
                'dniOwner' => 'required|string|min:9|max:9',
                'emailOwner' => 'Required|string|max:255',
                'mobileOwner' => 'required|numeric|min:000000001|max:999999999'
            ]);
            
            $owner = [
                "session_id" => intval($request->input('session_id')),
                "name" => $validateDataforms['owner'],
                "dni" => $validateDataforms['dniOwner'],
                "phone_number" => $validateDataforms['mobileOwner'],
                "email" => $validateDataforms['emailOwner']
            ];

            if ($request->input('name') !== null) {
                $assistantsArray = [
                    "nameAssistants" => $request->input('name'),
                    "dniAssistants" => $request->input('dni'),
                    "mobileAssistants" => $request->input('mobile'),
                    "ticket_id" => $request->input('ticket_id'),
                ];
            } else {
                $assistantsArray = [
                    "nameAssistants" => null,
                    "dniAssistants" => null,
                    "mobileAssistants" => null,
                    "ticket_id" => explode(',', $request->input('ticketsIdOwner')),
                    "ticketQuantity" => explode(',',$request->input('ticketsQuantityOwner')),
                ];
            }
           
            session(['owner' => $owner]);
            session(['assistants' => $assistantsArray]);

            $price = $request->input('priceToRedsys');
            $orderNumber = strval(time());


            if (intval($price) === 0 || env('ACTIVEREDSYS') === false) {

                $bodyResponse = [
                    "title" => "Felicitaciones",
                    "txnSuccess" => true,
                    "message" => "Pronto recibirá un correo electrónico con sus entradas",
                    "date" => date('d-m-Y'),
                    "hour" => date('H:i'),
                    "orderNumber" => $orderNumber,
                    "authCode" => "FRT" . $orderNumber
                ];

                //Si todo va bien con la compra inserto el registro del comprador en la BD
                self::insertPurchaser();
                return view('payment.confirmation', ['response' => $bodyResponse]);
            } else {
                $redsys = new RedsysAPI;
                $urlKO = route('payment.confirmation');
                $urlOK = route('payment.confirmation');

                $redsys->setParameter('DS_MERCHANT_AMOUNT', $price);
                $redsys->setParameter('DS_MERCHANT_CURRENCY', env('DS_MERCHANT_CURRENCY', '978'));
                $redsys->setParameter('DS_MERCHANT_MERCHANTCODE', env('DS_MERCHANT_MERCHANTCODE', '999008881'));
                $redsys->setParameter('DS_MERCHANT_ORDER', $orderNumber);
                $redsys->setParameter('DS_MERCHANT_TERMINAL', env('DS_MERCHANT_TERMINAL', '001'));
                $redsys->setParameter('DS_MERCHANT_TRANSACTIONTYPE', env('DS_MERCHANT_TRANSACTIONTYPE', '0'));
                $redsys->setParameter("DS_MERCHANT_DIRECTPAYMENT", "true");
                $redsys->setParameter("DS_REDSYS_ENVIROMENT", "true");
                $redsys->setParameter('DS_MERCHANT_URLKO', $urlKO);
                $redsys->setParameter('DS_MERCHANT_URLOK', $urlOK);

                $params = $redsys->createMerchantParameters();
                $signature = $redsys->createMerchantSignature(env('claveSHA256', 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'));
                $signatureVersion = env('Ds_SignatureVersion', 'HMAC_SHA256_V1');

                $content = '
                    <html>
                        <head>
                            <title>Formulario de pago</title>
                        </head>
                        <body>
                            <form name="form" action="' . env('URL_CONNECTION') . '" method="POST">
                                <input type="hidden" name="Ds_SignatureVersion" value="' . $signatureVersion . '"/>
                                <input type="hidden" name="Ds_MerchantParameters" value="' . $params . '"/>
                                <input type="hidden" name="Ds_Signature" value="' . $signature . '"/> 
                            </form>
                            <script>
                                document.form.submit();
                            </script>
                        </body>
                    </html>
                    ';
                $response = new Response();
                $response->setContent($content);

                return $response;
            }
        }  catch (ValidationException $e) {
            log::info('no paso validaciones');
            return redirect()->route('payment.index')
                ->withErrors($e->validator->getMessageBag())
                ->withInput();
        }catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    //Función que recibe el response de Redsys y muestra el feedback según el código de respuesta de la pasarela
    public function responseRedsys(Request $request)
    {
        try {
            Log::info('Llamada al método PaymentController.responseRedsys');

            $responseRedsys = new RedsysAPI;

            $response = $request->query();


            $version = $response['Ds_SignatureVersion'];
            $params = $response['Ds_MerchantParameters'];
            $receivedSignature = $response['Ds_Signature'];

            $paramsDeco = json_decode($responseRedsys->decodeMerchantParameters($params));

            //Verificar parametro "Ds_Signature para dirigir la respuesta a la vista

            $keyAdmin = env('claveSHA256', 'sq7HjrUOBfKmC576ILgskD5srU870gJ7');
            $calculatedKey = $responseRedsys->createMerchantSignatureNotif($keyAdmin, $params);

            // dd($paramsDeco);

            if ($receivedSignature === $calculatedKey && $paramsDeco->Ds_AuthorisationCode != "++++++") {

                $bodyResponse = [
                    "title" => "Felicitaciones",
                    "txnSuccess" => true,
                    "message" => "Pronto recibirá un correo electrónico con sus entradas",
                    "date" => str_replace("%2F", "-", $paramsDeco->Ds_Date),
                    "hour" => str_replace("%3A", ":", $paramsDeco->Ds_Hour),
                    "orderNumber" => $paramsDeco->Ds_Order,
                    "authCode" => $paramsDeco->Ds_AuthorisationCode
                ];

                //Si todo va bien con la compra inserto el registro del comprador en la BD
                self::insertPurchaser();
            } else {

                $bodyResponse = [
                    "title" => "Lo sentimos",
                    "txnSuccess" => false,
                    "message" => "Ha ocurrido un error durante el pago",
                    "date" => str_replace("%2F", "-", $paramsDeco->Ds_Date),
                    "hour" => str_replace("%3A", ":", $paramsDeco->Ds_Hour),
                    "orderNumber" => $paramsDeco->Ds_Order,
                    "responseCode" => $paramsDeco->Ds_Response,
                    "url_description" => env('URL_DESCRIPTION_ERROR')
                ];
            }

            return view('payment.confirmation', ['response' => $bodyResponse]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
