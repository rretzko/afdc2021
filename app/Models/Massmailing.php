<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massmailing extends Model
{
    use HasFactory;

    protected $fillable = ['audiencetype_id', 'eventversion_id','massmailingtype_id'];
    private $vars;

    public function findVar($descr) : string
    {
        return Massmailingvar::where('massmailing_id', $this->id)
            ->where('descr', $descr)
            ->first()->var ?? '';
    }

    public function massmailingvars()
    {
        return $this->hasMany(Massmailingvar::class)->orderBy('order_by');
    }

    public function paragraphs()
    {
        return $this->hasMany(Paragraph::class)->orderBy('order_by');
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

    private function replaceVars($paragraph)
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
                $descr = substr($part, $start, $finish); //ex: concert_date

                //define target
                $target = $descr.'*|';

                //find replacement
                $replacement = $this->findVar($descr);

                //replace target with correct value
                $parts[$key] = str_replace($target, $replacement, $part);
if(strpos($part,'oogle')){dd($parts[$key]);}
            }else{

                $parts[$key] = $part;
            }
        }

        return implode(' ',$parts);
    }
}
