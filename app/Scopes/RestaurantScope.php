<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class RestaurantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check() && Auth::user()->restaurant_id && Auth::user()->role !== 'super_admin') {
            $builder->where('restaurant_id', Auth::user()->restaurant_id);
        }
    }
}