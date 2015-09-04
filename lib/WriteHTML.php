<?php

require_once __DIR__ . '/../vendor/fpdf17/fpdf.php';

class PDF_HTML extends FPDF
{
    var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';

    var $lineHeight = 0;

    function footer()
    {
      // FIRMAS
      $texto = 'Firma del Director del Prestador de Servicios';
      $this->Cell(100, 5, utf8_decode($texto), 0, 0, 'L');
      $this->Cell(50, 5, '', 0, 0, 'L');
      $texto = 'Firma del Auditor de la Comisión Nacional';
      $this->Cell(100, 5, utf8_decode($texto), 0, 0, 'L');

      $this->Ln(5);

      $texto = 'de Formacion Profesional.';
      $this->Cell(15, 5, '', 0, 0, 'L');
      $this->Cell(50, 5, utf8_decode($texto), 0, 0, 'L');
      $texto = 'del Tránsito y la Seguridad Vial.';
      $this->Cell(95, 5, '', 0, 0, 'L');
      $this->Cell(90, 5, utf8_decode($texto), 0, 0, 'L');
    }

    function __construct ($orientation = 'P', $unit = 'pt', $format = 'Letter', $margin = 40, $lineHeight = 5) {
        $this->FPDF($orientation, $unit, $format);
        $this->SetTopMargin($margin);
        $this->SetLeftMargin($margin);
        $this->SetRightMargin($margin);
        $this->SetAutoPageBreak(true, $margin);

        $this->lineHeight = $lineHeight;
    }

    function WriteHTML($html)
    {
        //HTML parser
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);

        //print_r($a);exit();

        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                else
                    $this->Write($this->lineHeight,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }

    function OpenTag($tag,$prop)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
    }

    function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write($this->lineHeight,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}
