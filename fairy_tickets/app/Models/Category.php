<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function getCategorizablesCards()
    {
        try {
            Log::info('Llamada al método Category.getCategorizableCards');

            $categorieswhitEvents = null;
            $totalCategories = Category::getTotalCategories();
            for ($i = 0; $i < count($totalCategories); $i++) {
                $categoryName = $totalCategories[$i]->name;
                if ($categorieswhitEvents === null) {
                    $categories = DB::table(DB::raw('(SELECT DISTINCT ON (event) e.id, e.name as event, c.name as category, s.date, tt.price
                    FROM events e
                    INNER JOIN categories c ON c.id = e.category_id
                    INNER JOIN sessions s ON s.event_id = e.id
                    INNER JOIN ticket_types tt ON tt.session_id = s.id
                    ORDER BY event, tt.price) as sub'))
                        ->select('sub.id', 'sub.event', 'sub.category', 'sub.date', 'sub.price')
                        ->where('sub.category',$categoryName)
                        ->orderBy('sub.date')
                        ->orderBy('sub.event')
                        ->limit(env('EVENTSBYCATEGORY'))
                        ->get();
                    $categories = $categories->toArray();
                    $categorieswhitEvents = $categories;
                    // dd($categorieswhitEvents);
                } else {
                    $categories = DB::table(DB::raw('(SELECT DISTINCT ON (event) e.id, e.name as event, c.name as category, s.date, tt.price
                    FROM events e
                    INNER JOIN categories c ON c.id = e.category_id
                    INNER JOIN sessions s ON s.event_id = e.id
                    INNER JOIN ticket_types tt ON tt.session_id = s.id
                    ORDER BY event, tt.price) as sub'))
                        ->select('sub.id', 'sub.event', 'sub.category', 'sub.date', 'sub.price')
                        ->where('sub.category',$categoryName)
                        ->orderBy('sub.date')
                        ->orderBy('sub.event')
                        ->limit(env('EVENTSBYCATEGORY'))
                        ->get();
                    $categories = $categories->toArray();
                    $categorieswhitEvents = array_merge($categorieswhitEvents, $categories);
                }
            }
            // dd($categorieswhitEvents);
            return $categorieswhitEvents;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }


    public static function getCategories()
    {
        try {
            Log::info("Llamada al método Category.getCategories");

            $cat = DB::table('categories')
                ->select('categories.id','categories.name')
                ->get();
            return $cat;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
    public static function getTotalCategories()
    {
        try {
            Log::info("Llamada al método Category.getTotalCategories");

            $cat = DB::table('categories')
                ->join('events', 'events.category_id', '=', 'categories.id')
                ->selectRaw('categories.name,count(events.name) as total')
                ->groupBy('categories.id')
                ->orderBy('categories.name')
                ->get();
            return $cat;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
