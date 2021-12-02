<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massmailing extends Model
{
    use HasFactory;

    protected $fillable = ['audiencetype_id', 'eventversion_id','massmailingtype_id'];
    private $vars;

    public function paragraphs()
    {
        return $this->hasMany(Paragraph::class)->orderBy('order_by');
    }

    public function massmailingvars()
    {
        return $this->hasMany(Massmailingvar::class)->orderBy('order_by');
    }

    public function parse() : string
    {
        $str = '';
        $this->vars = $this->massmailingvars();

        foreach($this->paragraphs AS $paragraph){

            $str .= $this->replaceVars($paragraph->paragraph);
        }
        return $str;
    }

    public function replaceVars($paragraph)
    {
        //break string by |* variable header
        $parts = explode('|*',$paragraph);

        //get variables
        $vars = $this->vars->get();

        foreach($parts AS $key => $part){

            //search if replacements are necessary
            if(strpos($part, '*|')){

                //define target
                $start = '0';
                $finish = strpos($part, '*|');
                $descr = substr($part, $start, $finish);
//if(! $vars->where('descr', $target)->first()){ dd($target);}
                //define target
                $target = $descr.'*|';

                //find replacement
                $replacement = $vars->where('descr', $descr)->first()->var;
//dd($part.': '.$target.': '.$replacement);


                //replace target with correct value
                $parts[$key] = str_replace($target, $replacement, $part);

            }else{

                $parts[$key] = $part;
            }
        }

        return implode(' ',$parts);
    }
}
