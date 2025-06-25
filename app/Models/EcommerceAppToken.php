<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class EcommerceAppToken extends SanctumPersonalAccessToken
{
    // Point this model to use the new 'ecommerce_db_connection'
    protected $connection = 'ecommerce_db_connection';

    // Explicitly set the table name, just in case
    protected $table = 'personal_access_tokens';
}