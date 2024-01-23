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
                    $categories = DB::table('events')
                        ->join('locations', 'events.location_id', '=', 'locations.id')
                        ->join('categories', 'events.category_id', '=', 'categories.id')
                        ->selectRaw('events.id as id, events.name as event, categories.name as category, locations.name as location, events.price,events.date')
                        ->whereRaw('unaccent(lower(categories.name)) ILIKE unaccent(lower(?))', [$categoryName])
                        ->orderBy('events.date')
                        ->limit(env('EVENTSBYCATEGORY'))
                        ->get();
                    $categories = $categories->toArray();
                    $categorieswhitEvents = $categories;
                } else {
                    $categories = DB::table('events')
                        ->join('locations', 'events.location_id', '=', 'locations.id')
                        ->join('categories', 'events.category_id', '=', 'categories.id')
                        ->selectRaw('events.id as id, events.name as event, categories.name as category, locations.name as location, events.price, events.date')
                        ->whereRaw('unaccent(lower(categories.name)) ILIKE unaccent(lower(?))', [$categoryName])
                        ->orderBy('events.date')
                        ->limit(env('EVENTSBYCATEGORY'))
                        ->get();
                    $categories = $categories->toArray();
                    $categorieswhitEvents = array_merge($categorieswhitEvents, $categories);
                }
            }
            return $categorieswhitEvents;
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
