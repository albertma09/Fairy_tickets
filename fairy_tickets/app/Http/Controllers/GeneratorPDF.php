<?php

namespace App\Http\Controllers;


class GeneratorPDF extends Controller
{
    

public function generatePDF()
{
    
    return view('components.ticket-pdf');
}

}
