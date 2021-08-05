<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    /**
    * @author Alexandre
    */
    require_once("m_pdf/mpdf.php");

    function pdf_create_assination($html, $file = '')
    {
        $mpdf=new mPDF();
        $ano = date('Y');
        $mpdf->SetHTMLFooter('<div style="width: 100%; text-align: center; font-size:9px;">&copy; Desenvolvimento SATC - '.$ano.'</div>');
        $mpdf->WriteHTML($html);
        return $mpdf->Output();
    }

    function pdf_create($html, $file = '')
    {
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion=TRUE;
        $mpdf->charset_in='ISO-8859-1';
        $mpdf->SetHTMLFooter(
            "<hr style='margin: 0 0 5px !important;' />
            <div style='float:left; width:33.33%'>
                <div style='width: 100%; text-align: left; font-size: 12px;'>".DATE("d/m/Y H:i:s")."</div>
            </div>
            <div style='float:left; width:33.33%'>
                <div style='width: 100%; text-align: center; font-size: 12px; font-weight: bold;'>&#169; Desenvolvimento SATC</div>
            </div>
            <div style='float:left; width:33.33%;'>
                <div style='width: 100%; text-align: right; font-size: 12px; font-weight: bold;'>Página: {PAGENO}/{nb}</div>
            </div>"
        );
        $mpdf->AddPage();
        $mpdf->WriteHTML($html);
        return $mpdf->Output();
    }

    function pdf_create_portrait($html, $file = '', $local = '')
    {
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);

        if ($local!='') {
            $mpdf->Output($local.$file, 'F');
            return $mpdf->Output($local.$file, 'I');
        } else {
            return $mpdf->Output();
        }
    }

    function pdf_create_landscape($html, $file = '', $outros = '', $local = '')
    {
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

        if (trim($outros) != '') {
            $mpdf->SetHTMLHeader("
                    <div style='float:left; width:33.33%'>
                        <div style='width: 100%; text-align: left; font-size: 12px; font-weight: bold;'>Protocolo: {$outros}</div>
                    </div>
                    <div style='float:left; width:33.33%'>
                        <div style='width: 100%; text-align: center; font-size: 12px;'>".DATE("d/m/Y H:i:s")."</div>
                    </div>
                    <div style='float:left; width:33.33%;'>
                        <div style='width: 100%; text-align: right; font-size: 12px; font-weight: bold;'>Página: {PAGENO}/{nb}</div>
                    </div>
                    <hr style='margin: 5px 0 0 !important;' />");
            $mpdf->SetHTMLFooter("
                    <hr style='margin: 0 0 5px !important;' />
                    <div style='float:left; width:33.33%'>
                        <div style='width: 100%; text-align: left; font-size: 12px; font-weight: bold;'>Protocolo: {$outros}</div>
                    </div>
                    <div style='float:left; width:33.33%'>
                        <div style='width: 100%; text-align: center; font-size: 12px;'>".DATE("d/m/Y H:i:s")."</div>
                    </div>
                    <div style='float:left; width:33.33%;'>
                        <div style='width: 100%; text-align: right; font-size: 12px; font-weight: bold;'>Página: {PAGENO}/{nb}</div>
                    </div>");
        }
        $mpdf->AddPage();
        $mpdf->WriteHTML($html);
        $mpdf->Output($local.$file, 'F');
        return $mpdf->Output($local.$file, 'I');
    }

    function pdf_create_landscape_save($html, $file = '', $outros = '')
    {
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in='UTF-8';
        $mpdf->AddPage();
        @$mpdf->WriteHTML($html);
        @$mpdf->Output("./arquivos/relatorios/{$file}","F");
    }

    function pdf_create_rodape_satc($html)
    {
        $mpdf=new mPDF();
        $mpdf->SetHTMLFooter('<div style="width: 100%; text-align: center; font-size:9px;">
                              Rua Pascoal Meller, 73 - Bairro Universitário - Criciúma/SC<br>
                              CEP 88 805-380
                              </div>');
        $mpdf->WriteHTML($html);
        return $mpdf->Output();
    }

    function pdf_create_certificado($html)
    {
        $mpdf = new mPDF('', 'A4-L', '', '', 0, 0, 0, -1);
        $mpdf->allow_charset_conversion=TRUE;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);
        return $mpdf->Output();
    }

    function pdf_saveFromAPI($html, $param_hash)
    {
        $hash1 = hash('crc32', password_hash($param_hash, PASSWORD_BCRYPT));
        $hash1 = substr($hash1, 0, 4).'.'.substr($hash1, 4, 7);
        $hash2 = hash('crc32', password_hash($param_hash, PASSWORD_BCRYPT));
        $hash2 = substr($hash2, 0, 4).'.'.substr($hash2, 4, 7);

        $cod_auth = $hash1.'.'.$hash2;
        date_default_timezone_set('America/Sao_Paulo');
        $time = date('H:i:s');
        $date = date('d/m/Y');

        $mpdf = new mPDF('', 'A4-L', '', '', 0, 0, 0, -1);
        $mpdf->ignore_invalid_utf8 = true;

        $obj_rodape = "<p style='margin-top: 0px; margin-bottom: 0px; text-align: left;'>Documento emitido às: <b>{$time}</b>
                        do dia <b>{$date}</b> (hora e data de Brasília).<br>";
        $obj_rodape .= "Código de controle do documento: <b>{$cod_auth}</b><br>";
        $obj_rodape .= "Autenticidade poderá ser confirmada na página da instituição SATC
                        na Internet, no endereço <b>autentico.satc.edu.br</b>.</p>";

        $mpdf->SetHTMLFooter("<div class=\"rodape\">
                                <div class=\"rodBox\">
                                    <div class=\"aviso avisoAzul\">{$obj_rodape}</div>
                                </div>");

        $mpdf->WriteHTML($html);
        $file = $mpdf->Output('', 'S');

        $CI =& get_instance();
        $CI->load->library('Curl');

        $server = SERVER_API_DOC.'document/save';
        $post = ['extension'=>'pdf', 'file'=>$file, 'cod_auth'=>$cod_auth];
        $header = ['Content-Type: application/x-www-form-urlencoded; charset=utf-8'];
        $CI->curl->setHeader($header);
        $response = $CI->curl->post($server, $post);
        return $response;
        //return $mpdf->Output();
    }
