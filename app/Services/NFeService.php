<?php

namespace App\Services;

use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use App\Services\Connection;
use App\Services\ConDB;
use App\GE113NFE;


class NFeService {

    private $config;
    private $tools;

    public function save() 
    {
        $service = new Connection();
        return $service->save();
        //return $notas;
    }

    public function listNotasPendentes() 
    {
        $service = new Connection();
        $notas = $service->listNotasPendentes();
        return $notas;
    }


    /**
     * seleciona a nota
     */
    public function selecionarNota($num_nota_fiscal, $cod_serie_nf){
        $service = new Connection();
        return $service->selecionarNota($num_nota_fiscal, $cod_serie_nf);
    }

    /**
     * seleciona a nota
     */
    public function selecionarItens($num_nota_fiscal, $cod_serie_nf) 
    {
        $service = new Connection();
        return $service->selecionarItens($num_nota_fiscal, $cod_serie_nf);
    }

    function _construct(){      
        $arr = [
            "atualizacao" => "2017-02-20 09:11:21",
            "tpAmb"       => 2,
            "razaosocial" => "SUA RAZAO SOCIAL LTDA",
            "cnpj"        => "99999999999999",
            "siglaUF"     => "AM",
            "schemes"     => "PL_009_V4",
            "versao"      => '4.00',
            "tokenIBPT"   => "AAAAAAA",
            "CSC"         => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
            "CSCid"       => "000001",
            "proxyConf"   => [
                "proxyIp"   => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];
        $config = json_encode($arr);
        $pfxcontent = file_get_contents('../app/fixtures/07200194000380.pfx');        
        $this->tools = new Tools( $this->$config, Certificate::readPfx($pfxcontent, 'calcomp01'));
        $this->$tools->model('65');
    }

    public function gerarNFe($nota, $itens)
    {
        try {
            $xml = gerarXml($nota, $itens);
            header('Content-Type: application/xml; charset=utf-8');

            return $xmlAssinado;           
                        
        } catch (\Exception $e) {
            echo $e->getMessage();
            
        } 
    }

    public function gerarXml($nota, $itens)
    {
        $arr = [
            "atualizacao" => "2017-02-20 09:11:21",
            "tpAmb"       => 2,
            "razaosocial" => "SUA RAZAO SOCIAL LTDA",
            "cnpj"        => "99999999999999",
            "siglaUF"     => "AM",
            "schemes"     => "PL_009_V4",
            "versao"      => '4.00',
            "tokenIBPT"   => "AAAAAAA",
            "CSC"         => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
            "CSCid"       => "000001",
            "proxyConf"   => [
                "proxyIp"   => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];
        $configJson = json_encode($arr);
        $pfxcontent = file_get_contents('../app/fixtures/07200194000380.pfx');
        
        $tools = new Tools( $configJson, Certificate::readPfx($pfxcontent, 'calcomp01'));
        $tools->model('65');
        //$tools->disableCertValidation(true); //tem que desabilitar
        //$this->getTools()->model('65');
        try {        
            $make = new Make();        
        
            //infNFe OBRIGATÓRIA
            $stdInfNFe = new \stdClass();
            $stdInfNFe->Id = '';
            $stdInfNFe->versao = $nota["NUM_VERSAO_A2"];//'4.00';
            $stdInfNFe->pk_nItem = null;
            $infNFe = $make->taginfNFe($stdInfNFe);
        
            //IDENTIFICAÇÃO OBRIGATÓRIA
            $stdIde = new \stdClass();
            $stdIde->cUF = $nota["COD_UF_EMITENTE_NFE"];//13;
            $stdIde->cNF = rand(11111111,99999999);//numero aleatório para diferenciar a nota
            $stdIde->natOp = $nota["DES_OPERACAO"];//'RETORNO MERC.P/IND. NAO APLICD';
            $stdIde->mod = $nota["COD_MODELO_DOC_FISCAL"];//55;
            $stdIde->serie = $nota["COD_SERIE_NF"];
            $stdIde->nNF = $nota["N_NF"];
            $stdIde->dhEmi = $nota["DAT_EMISSAO_NFE"];//->format('d-m-Y H:i:s');;
            //AJUSTAR DATA DE EMISSÃO
            //$stdIde->dhEmi = (new \DateTime())->format('Y-m-d\TH:i:sP');
            $stdIde->dhSaiEnt = $nota["DAT_ENT_SAI"];
            $stdIde->tpNF = $nota["TP_NF"]; // entrada ou saida
            $stdIde->idDest =  $nota["ID_DEST"];//1; // dentro ou fora do estado
            $stdIde->cMunFG =  $nota["COD_IBGE_CIDADE"];
            //tpImp : 1=DANFE normal, Retrato; 2=DANFE normal, Paisagem; 
            $stdIde->tpImp = $nota["TIP_IMPRESSAO_DANFE"];
            //tpEmis : 1=Emissão normal (não em contingência);
            $stdIde->tpEmis = 1;
            $stdIde->cDV = 9;//TODO VERIFICAR
            $stdIde->tpAmb = 2;
            //COD_FINALIDADE_NFE: 1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devolução de mercadoria.
            $stdIde->finNFe = $nota["COD_FINALIDADE_NFE"];//1;
            $stdIde->indFinal = 1;
            //indPres 0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
            // 1=Operação presencial;
            // 2=Operação não presencial, pela Internet;
            // 3=Operação não presencial, Teleatendimento;
            // 4=NFC-e em operação com entrega a domicílio;
            // 9=Operação não presencial, outros
            $stdIde->indPres = 3;
            // 0=Emissão de NF-e com aplicativo do contribuinte;
            // 1=Emissão de NF-e avulsa pelo Fisco;
            // 2=Emissão de NF-e avulsa, pelo contribuinte com seu
            // certificado digital, através do site do Fisco;
            // 3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco
            $stdIde->procEmi = 0;
            $stdIde->verProc =  $nota["NUM_VERSAO_A2"];//'4.00';
            $stdIde->dhCont = null;
            $stdIde->xJust = null;
            $ide = $make->tagIde($stdIde);
            
            //EMITENTE OBRIGATÓRIA
            $stdEmit = new \stdClass();
            $stdEmit->xNome = $nota["DES_RAZAO_SOCIAL"];
            $stdEmit->xFant = $nota["DES_RAZAO_SOCIAL"];
            $stdEmit->IE =  $nota["COD_INSCRICAO_ESTADUAL"];
            $stdEmit->IEST = null;
            //$std->IM = '95095870';
            // $stdEmit->CNAE = '4642701';
            $stdEmit->CRT = $nota["COD_REGIME_TRIBUT"];
            $stdEmit->CNPJ = $nota["COD_CGC"];
            //$std->CPF = '12345678901'; //NÃO PASSE TAGS QUE NÃO EXISTEM NO CASO
            $emit = $make->tagemit($stdEmit);
        
            //enderEmit OBRIGATÓRIA
            $stdEnderEmit = new \stdClass();
            $stdEnderEmit->xLgr = $nota["DES_ENDERECO"];
            $stdEnderEmit->nro = $nota["NUM_ENDERECO"];
            $stdEnderEmit->xCpl = null;
            $stdEnderEmit->xBairro = $nota["DES_BAIRRO"];
            $stdEnderEmit->cMun = $nota["COD_IBGE_CIDADE"];
            $stdEnderEmit->xMun = $nota["DES_MUNICIPIO"];
            $stdEnderEmit->UF = $nota["COD_UF"];
            $stdEnderEmit->CEP = $nota["COD_CEP"];
            $stdEnderEmit->cPais = $nota["COD_PAIS_NFE"];
            $stdEnderEmit->xPais = $nota["NOM_PAIS"];
            $stdEnderEmit->fone = null;
            $ret = $make->tagenderemit($stdEnderEmit);
        
            //DESTINATARIO OPCIONAL
            $stdDest = new \stdClass();
            $stdDest->xNome = $nota["NOM_CLIENTE"];
            $stdDest->CNPJ = $nota["COD_CGC_CLIENTE"];
            //$std->CPF = '12345678901';
            //$std->idEstrangeiro = 'AB1234';
            $stdDest->indIEDest = 1;
            $stdDest->IE = $nota["NUM_INSCRICAO_ESTADUAL"];
            //$std->ISUF = '12345679';
            //$std->IM = 'XYZ6543212';
            $stdDest->email = null;
            $dest = $make->tagdest($stdDest);
        
            //enderDest OPCIONAL
            $enderDest = new \stdClass();
            $enderDest->xLgr = $nota["NOM_FATURA_LOGRADOURO"];
            $enderDest->nro = $nota["NUM_FATURA_NUMERO"];
            $enderDest->xCpl = $nota["NOM_FATURA_COMPLEMENTO"];
            $enderDest->xBairro = $nota["NOM_FATURA_BAIRRO"];
            $enderDest->cMun = $nota["COD_FATURA_CIDADE"];
            $enderDest->xMun = $nota["NOM_FATURA_CIDADE"];
            $enderDest->UF = $nota["COD_FATURA_UF"];
            $enderDest->CEP = $nota["COD_FATURA_CEP"];
            $enderDest->cPais = $nota["COD_PAIS_NFE"];
            $enderDest->xPais = $nota["NOM_PAIS"];
            $enderDest->fone = $nota["COD_FATURA_TELEFONE"];
            $ret = $make->tagenderdest($enderDest);
        
            //PRODUTO OBRIGATÓRIA
            $indice = 0;
            foreach( $itens as $item ) {

                $indice = $indice + 1;
                $stdProd = new \stdClass();
                $stdProd->item = $indice;
                $stdProd->cProd = $item["COD_PRODUTO"];
                $stdProd->cEAN = $item["COD_EAN"];
                $stdProd->xProd =  $item["DES_PRODUTO"];
                $stdProd->NCM = $item["COD_CLASS_FISCAL_IPI"];
                //$stdProd->cBenef = 'ab222222';
                $stdProd->EXTIPI = ''; // TODO VERIFICAR
                $stdProd->CFOP = $item["COD_FISCAL_OPERACAO"];
                $stdProd->uCom = $item["COD_UNIDADE_MEDIDA"];
                $stdProd->qCom = $item["QTD_PRODUTO"];
                $stdProd->vUnCom = $item["VAL_UNITARIO_NF"];
                $stdProd->vProd = $item["VAL_TOTAL_PRODUTO"];
                $stdProd->cEANTrib = $item["COD_EAN"];
                $stdProd->uTrib = $item["UTRIB"];
                $stdProd->qTrib = $item["QTRIB"];
                $stdProd->vUnTrib = $item["VUNTRIB"];
                //$stdProd->vFrete = $item["VAL_FRETE"]; //TODO VERIFICAR
                $stdProd->vSeg = $item["VAL_SEGURO"]; //TODO VERIFICAR
                //$stdProd->vDesc = 0; //TODO VERIFICAR
                //$stdProd->vOutro = 0; //TODO VERIFICAR
                // indTot  0=Valor do item (vProd) não compõe o valor total da NF-e
                // 1=Valor do item (vProd) compõe o valor total da NF-e (vProd) (v2.0)
                $stdProd->indTot = 1; //TODO VERIFICAR
                $stdProd->xPed = $item["NUM_PEDIDO_CLIENTE"];
                //$stdProd->nItemPed = 1;
                //$stdProd->nFCI = '12345678-1234-1234-1234-123456789012';
                $prod = $make->tagprod($stdProd);
                /*
                $tag = new \stdClass();
                $tag->item = $indice;
                $tag->infAdProd = 'DE POLIESTER 100%';
                $make->taginfAdProd($tag);
                */
                //TODO VERIFICAR A NECESSIDADE DO CEST
                //$std = new \stdClass();
                //$std->item = $indice; //item da NFe
                //$std->CEST = $item["COD_CEST"];
                //$std->indEscala = 'N'; //TODO VERIFICAR
                //$std->CNPJFab = '12345678901234'; //TODO VERIFICAR
                //$make->tagCEST($std);
            
                //Imposto 
                $stdImposto = new \stdClass();
                $stdImposto->item = $indice; 
                //$stdImposto->vTotTrib = 25.00;
                $make->tagimposto($stdImposto);                

                /** ICMS */
                $stdICMS = new \stdClass();
                $stdICMS->item = $indice;
                $stdICMS->orig = $item["COD_ORIGEM_PRODUTO"];
                $stdICMS->CST = $item["COD_SIT_TRIBUTARIA_ICMS"];
                $stdICMS->modBC = 3; // TODO verificar com a NATALIA
                $stdICMS->vBC = $item["VAL_TOTAL_PRODUTO"];
                $stdICMS->pICMS = $item["VAL_ALIQUOTA_ICMS"];
                $stdICMS->vICMS = $item["VAL_ICMS"];
                $ICMS = $make->tagICMS($stdICMS);

                $stdIPI = new \stdClass();
                $stdIPI->item =  $indice;
                $stdIPI->cEnq = '335';//TODO verificar
                $stdIPI->CST = '52';//TODO verificar
                $stdIPI->vIPI = 0;
                $stdIPI->vBC = 0;
                $stdIPI->pIPI = 0;
                $IPI = $make->tagIPI($stdIPI);

                /** PIS */
                $stdPIS = new \stdClass();
                $stdPIS->item = $indice;
                $stdPIS->CST = '02';//TODO
                $stdPIS->vBC = $item["VAL_TOTAL_PRODUTO"];
                $stdPIS->pPIS = $item["VAL_ALIQUOTA_PIS"];
                $stdPIS->vPIS = $item["VAL_PIS"];
                $PIS = $make->tagPIS($stdPIS);


                /** COFINS */
                $stdCOFINS = new \stdClass();
                $stdCOFINS->item = $indice;
                //$stdPIS->orig = 0;
                $stdCOFINS->CST = '02';
                //$stdPIS->modBC = 0;
                $stdCOFINS->vBC = $item["VAL_TOTAL_PRODUTO"];
                $stdCOFINS->pCOFINS = $item["VAL_ALIQUOTA_COFINS"];
                $stdCOFINS->vCOFINS = $item["VAL_COFINS"];
                $COFINS = $make->tagCOFINS($stdCOFINS);
    
            }

            //icmstot OBRIGATÓRIA
            $stdIcmstot = new \stdClass();
            $stdIcmstot->vBC = $item["VAL_BASE_ICMS"];
            $stdIcmstot->vICMS = $item["VAL_ICMS"];
            //$stdIcmstot->vICMSDeson = $nota["VAL_ICMS"]; //TODO VERIFICAR
            //$stdIcmstot->vFCP = $nota["VAL_ICMS"]; //TODO VERIFICAR
            //$stdIcmstot->vBCST = $nota["VAL_ICMS"]; //TODO VERIFICAR
            //$stdIcmstot->vST = $nota["VAL_ICMS"]; //TODO VERIFICAR
            //$stdIcmstot->vFCPST = $nota["VAL_ICMS"]; //TODO VERIFICAR
            //$stdIcmstot->vFCPSTRet = $nota["VAL_ICMS"]; //TODO VERIFICAR
            $stdIcmstot->vProd = $nota["VAL_MERCADORIA"]; //TODO VERIFICAR
            //$stdIcmstot->vFrete = $nota["VAL_FRETE"];
            $stdIcmstot->vSeg = $nota["VAL_SEGURO"];
            $stdIcmstot->vDesc = $nota["VAL_DESCONTO"];
            //$stdIcmstot->vII = $nota["VAL_DESCONTO"]; //TODO VERIFICAR
            $stdIcmstot->vIPI = $nota["VAL_IPI"];
            //$stdIcmstot->vIPIDevol = $nota["VAL_IPI"];//TODO VERIFICAR
            $stdIcmstot->vPIS = $nota["VAL_PIS"];
            $stdIcmstot->vCOFINS = $nota["VAL_COFINS"];
            //$stdIcmstot->vOutro = $nota["VAL_TOTAL_NF"];//TODO VERIFICAR
            $stdIcmstot->vNF = $nota["VAL_TOTAL_NF"];
            $icmstot = $make->tagicmstot($stdIcmstot);
        
            //transp OBRIGATÓRIA
            $stdTransp = new \stdClass();
            $stdTransp->modFrete = 0;
            $transp = $make->tagtransp($stdTransp);  

            $stdPag = new \stdClass();
            //$stdPag->vTroco = 0;
            $pag = $make->tagpag($stdPag);
        
            //detPag OBRIGATÓRIA
            $stdDetPag = new \stdClass();
            //$stdDetPag->indPag = 1; //TODO VERIFICAR
            $stdDetPag->tPag = '90'; //TODO VERIFICAR
            $stdDetPag->vPag = 0.00; //TODO VERIFICAR
            $detpag = $make->tagdetpag($stdDetPag); 
        
            //infadic
            $stdInfadic = new \stdClass();
            $stdInfadic->infAdFisco = '';
            $stdInfadic->infCpl = '';
            $info = $make->taginfadic($stdInfadic);
        
            $stdResp = new \stdClass();
            $stdResp->CNPJ = '07200194000118'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
            $stdResp->xContato = 'claudemir@gmail.com'; //Nome da pessoa a ser contatada
            $stdResp->email = 'claudemir@mail.com'; //E-mail da pessoa jurídica a ser contatada
            $stdResp->fone = '1155551122'; //Telefone da pessoa jurídica/física a ser contatada
            //$std->CSRT = 'G8063VRTNDMO886SFNK5LDUDEI24XJ22YIPO'; //Código de Segurança do Responsável Técnico
            //$std->idCSRT = '01'; //Identificador do CSRT
            $make->taginfRespTec($stdResp);
            $resp = $make->monta();
            $xml = $make->getXML();
            file_put_contents("notas/" .$nota["NUM_NOTA_FISCAL"] ."_" .$nota["COD_SERIE_NF"] .'.xml',$xml);

            $xmlAssinado = $tools->signNFe($xml);// $tools->signNFe($xml); //$this->getID_ADDITION()
            //salva o arquivo
            file_put_contents("notas/" .$nota["NUM_NOTA_FISCAL"] ."_" .$nota["COD_SERIE_NF"] .'_sign.xml',$xmlAssinado);
            //echo $xmlAssinado;
            return $xmlAssinado;           
                        
        } catch (\Exception $e) {
            file_put_contents("notas/" .$nota["NUM_NOTA_FISCAL"] ."_" .$nota["COD_SERIE_NF"] .'erro.xml',$xml);
            echo $e;
            //print_r(array_values ($make->getErrors()));
            return $make->getErrors();            
        } 
    }

    public function sign( $xml){
        $arr = [
            "atualizacao" => "2017-02-20 09:11:21",
            "tpAmb"       => 2,
            "razaosocial" => "SUA RAZAO SOCIAL LTDA",
            "cnpj"        => "99999999999999",
            "siglaUF"     => "AM",
            "schemes"     => "PL_009_V4",
            "versao"      => '4.00',
            "tokenIBPT"   => "AAAAAAA",
            "CSC"         => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
            "CSCid"       => "000001",
            "proxyConf"   => [
                "proxyIp"   => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];
        $configJson = json_encode($arr);
        $pfxcontent = file_get_contents('../app/fixtures/07200194000380.pfx');
        
        $tools = new Tools( $configJson, Certificate::readPfx($pfxcontent, 'calcomp01'));
        return $tools->signNFe($xml);
    }

    public function transmitir( $xmlAssinado){
        $arr = [
            "atualizacao" => "2017-02-20 09:11:21",
            "tpAmb"       => 2,
            "razaosocial" => "SUA RAZAO SOCIAL LTDA",
            "cnpj"        => "99999999999999",
            "siglaUF"     => "AM",
            "schemes"     => "PL_009_V4",
            "versao"      => '4.00',
            "tokenIBPT"   => "AAAAAAA",
            "CSC"         => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
            "CSCid"       => "000001",
            "proxyConf"   => [
                "proxyIp"   => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];
        $configJson = json_encode($arr);
        $pfxcontent = file_get_contents('../app/fixtures/07200194000380.pfx');
        
        $tools = new Tools( $configJson, Certificate::readPfx($pfxcontent, 'calcomp01'));
        //$idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
        $resp = $tools->sefazEnviaLote([$xmlAssinado], 1, 1);
        $st = new Standardize();
        $stdResposta = $st->toStd($resp);
        //dd($stdResposta);
        file_put_contents("stdResposta.xml",$stdResposta);
        return $stdResposta;
        /*
        if ($std->cStat != 103) {
            //erro registrar e voltar
            exit("[$std->cStat] $std->xMotivo");
         }
         $recibo = $std->infRec->nRec;
        return $tools->sefazEnviaLote();
        */
    }

    public function getTools() {
        return $this->tools;
    }
      
    public function setTools($tools) {
        $this->tools= $tools;
    }

    public function salvarGe113nfe($xml) {

        $sql = "INSERT INTO GE113NFETEST ( NUM_RECIBO, NUM_NF_INTERNO, XML_ASSINATURA) VALUES ( '111', '222', EMPTY_CLOB()) RETURNING  XML_ASSINATURA INTO :XML_ASSINATURA";
        $conn = new Connection();
        $conn->AbrirConexao();
        //$stmt = oci_parse($conn, $sql);

        //
        $ora_conecta = $conn->getOra_conecta();
        $stmt = $ora_conecta->prepare($sql);

        $file = '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe13200807200194000118550020000821421180953623" versao="4.00"><ide><cUF>13</cUF><cNF>18095362</cNF><natOp>COMPRA P/ INDUSTRIALIZACAO-IMP</natOp><mod>55</mod><serie>2</serie><nNF>82142</nNF><dhEmi>2020-08-14T11:45:50-04:00</dhEmi><dhSaiEnt>2020-08-14T11:45:50-04:00</dhSaiEnt><tpNF>1</tpNF><idDest>3</idDest><cMunFG>1302603</cMunFG><tpImp>1</tpImp><tpEmis>1</tpEmis><cDV>3</cDV><tpAmb>2</tpAmb><finNFe>1</finNFe><indFinal>1</indFinal><indPres>3</indPres><procEmi>0</procEmi><verProc>4.00</verProc></ide><emit><CNPJ>07200194000118</CNPJ><xNome>CAL-COMP IND.COM.ELETR. INFORM LTDA</xNome><xFant>CAL-COMP IND.COM.ELETR. INFORM LTDA</xFant><enderEmit><xLgr>AV. TORQUATO TAPAJOS</xLgr><nro>7503</nro><xBairro>TARUMA</xBairro><cMun>1302603</cMun><xMun>MANAUS</xMun><UF>AM</UF><CEP>69041025</CEP><cPais>7765</cPais><xPais>TAILANDIA</xPais></enderEmit><IE>063003848</IE><CRT>3</CRT></emit><dest><CNPJ>00000000000000</CNPJ><xNome>NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL</xNome><enderDest><xLgr>RACHADAPISEK ROAD N° 191 54-57</xLgr><nro>19154</nro><xCpl>18TH FLOOR, CTI TOWE</xCpl><xBairro>KLONGTOEY</xBairro><cMun>9999999</cMun><xMun>EXTERIOR</xMun><UF>EX</UF><CEP>00010110</CEP><cPais>7765</cPais><xPais>TAILANDIA</xPais><fone>0</fone></enderDest><indIEDest>2</indIEDest></dest><det nItem="1"><prod><cProd>MAPWM387AA4</cProd><cEAN>SEM GTIN</cEAN><xProd>BTP M3*8L OD7 PW NI</xProd><NCM>73181500</NCM><CFOP>3101</CFOP><uCom>PC</uCom><qCom>250000</qCom><vUnCom>.04370764</vUnCom><vProd>10926.91</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>KG</uTrib><qTrib>250000</qTrib><vUnTrib>.04370764</vUnTrib><indTot>1</indTot></prod><imposto><ICMS><ICMS40><orig>1</orig><CST>41</CST></ICMS40></ICMS><IPI><cEnq>335</cEnq><IPINT><CST>52</CST></IPINT></IPI><PIS><PISAliq><CST>02</CST><vBC>10926.91</vBC><pPIS>0.0000</pPIS><vPIS>0.00</vPIS></PISAliq></PIS><COFINS><COFINSAliq><CST>02</CST><vBC>10926.91</vBC><pCOFINS>0.0000</pCOFINS><vCOFINS>0.00</vCOFINS></COFINSAliq></COFINS></imposto></det><total><ICMSTot><vBC>0.00</vBC><vICMS>0.00</vICMS><vICMSDeson>0.00</vICMSDeson><vFCP>0.00</vFCP><vBCST>0.00</vBCST><vST>0.00</vST><vFCPST>0.00</vFCPST><vFCPSTRet>0.00</vFCPSTRet><vProd>10926.91</vProd><vFrete>0.00</vFrete><vSeg>0.00</vSeg><vDesc>0.00</vDesc><vII>0.00</vII><vIPI>0.00</vIPI><vIPIDevol>0.00</vIPIDevol><vPIS>0.00</vPIS><vCOFINS>0.00</vCOFINS><vOutro>0.00</vOutro><vNF>10926.91</vNF></ICMSTot></total><transp><modFrete>0</modFrete></transp><pag><detPag><tPag>90</tPag><vPag>0.00</vPag></detPag></pag><infAdic/><infRespTec><CNPJ>07200194000118</CNPJ><xContato>claudemir@gmail.com</xContato><email>claudemir@mail.com</email><fone>1155551122</fone></infRespTec></infNFe></NFe>';
        
        $file = oci_new_descriptor($conn, OCI_D_LOB);
        oci_bind_by_name($stmt, ":file", $file, -1, OCI_B_CLOB);

        oci_execute($stmt, OCI_DEFAULT)
            or die ("Unable to execute query\n");

        if ( !$file->save('INSERT: '.date('H:i:s',time())) ) {
            oci_rollback($conn);            
        } else {
            oci_commit($conn);
        }
        oci_free_statement($stmt);
        $file->free();
    }



    public function salvarGe113nfe1($xml) {
        //$file = '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe13200807200194000118550020000821421180953623" versao="4.00"><ide><cUF>13</cUF><cNF>18095362</cNF><natOp>COMPRA P/ INDUSTRIALIZACAO-IMP</natOp><mod>55</mod><serie>2</serie><nNF>82142</nNF><dhEmi>2020-08-14T11:45:50-04:00</dhEmi><dhSaiEnt>2020-08-14T11:45:50-04:00</dhSaiEnt><tpNF>1</tpNF><idDest>3</idDest><cMunFG>1302603</cMunFG><tpImp>1</tpImp><tpEmis>1</tpEmis><cDV>3</cDV><tpAmb>2</tpAmb><finNFe>1</finNFe><indFinal>1</indFinal><indPres>3</indPres><procEmi>0</procEmi><verProc>4.00</verProc></ide><emit><CNPJ>07200194000118</CNPJ><xNome>CAL-COMP IND.COM.ELETR. INFORM LTDA</xNome><xFant>CAL-COMP IND.COM.ELETR. INFORM LTDA</xFant><enderEmit><xLgr>AV. TORQUATO TAPAJOS</xLgr><nro>7503</nro><xBairro>TARUMA</xBairro><cMun>1302603</cMun><xMun>MANAUS</xMun><UF>AM</UF><CEP>69041025</CEP><cPais>7765</cPais><xPais>TAILANDIA</xPais></enderEmit><IE>063003848</IE><CRT>3</CRT></emit><dest><CNPJ>00000000000000</CNPJ><xNome>NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL</xNome><enderDest><xLgr>RACHADAPISEK ROAD N° 191 54-57</xLgr><nro>19154</nro><xCpl>18TH FLOOR, CTI TOWE</xCpl><xBairro>KLONGTOEY</xBairro><cMun>9999999</cMun><xMun>EXTERIOR</xMun><UF>EX</UF><CEP>00010110</CEP><cPais>7765</cPais><xPais>TAILANDIA</xPais><fone>0</fone></enderDest><indIEDest>2</indIEDest></dest><det nItem="1"><prod><cProd>MAPWM387AA4</cProd><cEAN>SEM GTIN</cEAN><xProd>BTP M3*8L OD7 PW NI</xProd><NCM>73181500</NCM><CFOP>3101</CFOP><uCom>PC</uCom><qCom>250000</qCom><vUnCom>.04370764</vUnCom><vProd>10926.91</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>KG</uTrib><qTrib>250000</qTrib><vUnTrib>.04370764</vUnTrib><indTot>1</indTot></prod><imposto><ICMS><ICMS40><orig>1</orig><CST>41</CST></ICMS40></ICMS><IPI><cEnq>335</cEnq><IPINT><CST>52</CST></IPINT></IPI><PIS><PISAliq><CST>02</CST><vBC>10926.91</vBC><pPIS>0.0000</pPIS><vPIS>0.00</vPIS></PISAliq></PIS><COFINS><COFINSAliq><CST>02</CST><vBC>10926.91</vBC><pCOFINS>0.0000</pCOFINS><vCOFINS>0.00</vCOFINS></COFINSAliq></COFINS></imposto></det><total><ICMSTot><vBC>0.00</vBC><vICMS>0.00</vICMS><vICMSDeson>0.00</vICMSDeson><vFCP>0.00</vFCP><vBCST>0.00</vBCST><vST>0.00</vST><vFCPST>0.00</vFCPST><vFCPSTRet>0.00</vFCPSTRet><vProd>10926.91</vProd><vFrete>0.00</vFrete><vSeg>0.00</vSeg><vDesc>0.00</vDesc><vII>0.00</vII><vIPI>0.00</vIPI><vIPIDevol>0.00</vIPIDevol><vPIS>0.00</vPIS><vCOFINS>0.00</vCOFINS><vOutro>0.00</vOutro><vNF>10926.91</vNF></ICMSTot></total><transp><modFrete>0</modFrete></transp><pag><detPag><tPag>90</tPag><vPag>0.00</vPag></detPag></pag><infAdic/><infRespTec><CNPJ>07200194000118</CNPJ><xContato>claudemir@gmail.com</xContato><email>claudemir@mail.com</email><fone>1155551122</fone></infRespTec></infNFe></NFe>';
        $file = '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe13200807200194000118550020000821421180953623" ';
        //echo $file;
        $arq = base64_encode($file );
        echo $arq;
        $msgRetorno = 0;
        $db = new Connection();
        try {
            if ($db->AbrirConexao()) {
                $ora_conecta = $db->getOra_conecta();
                $sql = 'INSERT INTO GE113NFETEST VALUES (?,?,?)';
                //$sql = ' CALL PROC_SAVE_GE113NFE (?,?,?)';
                $resultado = $ora_conecta->prepare($sql);
                $resultado->bindValue(1, '333');
                $resultado->bindValue(2, '333');
                //$resultado->bindValue(3, 'tttttttttt');
                $resultado->bindValue(3, 'PE5GZSB4bWxucz0iaHR0cDovL3d3dy5wb3J0YWxmaXNjYWwuaW5mLmJyL25mZSI+PGluZk5GZSBJZD0iTkZlMTMyMDA4MDcyMDAxOTQwMDAxMTg1NTAwMjAwMDA4MjE0MjExODA5NTM2MjMiIHZlc',   \PDO::PARAM_STR, 4000);

                try {
                    //$ora_conecta->beginTransaction();
                    $resultado->execute();
                    //$ora_conecta->commit();
                    $db->fecharConexao();
                } catch (PDOException $e) {
                    echo "DataBase Error: The user could not be added.<br>".$e->getMessage();
                } catch (Exception $e) {
                    echo "General Error: The user could not be added.<br>".$e->getMessage();
                }
            }
        } catch (PDOException $e) {
           return  'Error : ' . $e->getMessage();
        }

        return $msgRetorno;
    }

    public function salvarXml($num_nota_fiscal, $cod_serie_nf){  
        
        $image_source = "003799_10.xml";
        $image = fopen($image_source, 'r');
        $image_string = fread($image, filesize($image_source));
        $img = base64_encode($image_string);
        
        //$xml = base64_encode(fread(fopen($_FILES['003799_10.xml']['003799_10'], "r")));

        $service = new Connection();
        return $service->saveNFe($img);
    }

    public function salvarXml1($xml){  
        
        $image_source = "003799_10.xml";
        $image = fopen($image_source, 'r');
        $image_string = fread($image, filesize($image_source));
        $img = base64_encode($image_string);
        
        //$xml = base64_encode(fread(fopen($_FILES['003799_10.xml']['003799_10'], "r")));

        $service = new Connection();
        return $service->saveNFe($xml);
    }

}