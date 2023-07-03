<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evalution extends Model {
    protected $table = 'evaluation';
    protected $primaryKey = 'evaluation_id';

    protected $fillable = ['version','initiative_name','scorecard_name'];
}