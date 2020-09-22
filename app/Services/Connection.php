<?php

namespace App\Services;

class Connection {

    public static $conn;

    private $ora_conecta;
    
    public function AbrirConexao() {
        $ret = false;
        try {
            $this->ora_conecta = new \PDO($this->ora_dsn . ";charset=utf8", $this->ora_user, $this->ora_senha);
            $ret = true;
        } catch (\PDOException $e) {
            $e->getMessage();
            echo "<p>Nao foi possivel conectar-se ao servidor Oracle.</p>\n"
                .
                "<p><strong>Erro Oracle: " . $e . "</strong></p>\n";
                exit();
            }                
        return $ret;
    }

    public function fecharConexao() {
        return $this->ora_conecta = null;
    }

    function getOra_conecta() {
        return $this->ora_conecta;
    }

    public function save(){  
        
        $file = '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe13200807200194000118550020000821421180953623" ';
        echo $file;
        $blob1 = base64_encode($file);
        echo $blob1;

        $this->AbrirConexao();
        $sql = "insert into mylobs (mylob) values ( empty_blob()) returning data1 into :blob1 ";
        $stmt = $this->ora_conecta->prepare($sql);
        $stmt->bindParam(':blob1', $blob1, \PDO::PARAM_LOB);
        $blob1 = null;
        $stmt->execute();

        //fwrite($blob1, $data1);  
        //fclose($blob1);        
        $this->fecharConexao();
        return $blob1;
    }

    public function save1() 
    {
        
       //$file = '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe13200807200194000118550020000821421180953623" versao="4.00"><ide><cUF>13</cUF><cNF>18095362</cNF><natOp>COMPRA P/ INDUSTRIALIZACAO-IMP</natOp><mod>55</mod><serie>2</serie><nNF>82142</nNF><dhEmi>2020-08-14T11:45:50-04:00</dhEmi><dhSaiEnt>2020-08-14T11:45:50-04:00</dhSaiEnt><tpNF>1</tpNF><idDest>3</idDest><cMunFG>1302603</cMunFG><tpImp>1</tpImp><tpEmis>1</tpEmis><cDV>3</cDV><tpAmb>2</tpAmb><finNFe>1</finNFe><indFinal>1</indFinal><indPres>3</indPres><procEmi>0</procEmi><verProc>4.00</verProc></ide><emit><CNPJ>07200194000118</CNPJ><xNome>CAL-COMP IND.COM.ELETR. INFORM LTDA</xNome><xFant>CAL-COMP IND.COM.ELETR. INFORM LTDA</xFant><enderEmit><xLgr>AV. TORQUATO TAPAJOS</xLgr><nro>7503</nro><xBairro>TARUMA</xBairro><cMun>1302603</cMun><xMun>MANAUS</xMun><UF>AM</UF><CEP>69041025</CEP><cPais>7765</cPais><xPais>TAILANDIA</xPais></enderEmit><IE>063003848</IE><CRT>3</CRT></emit><dest><CNPJ>00000000000000</CNPJ><xNome>NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL</xNome><enderDest><xLgr>RACHADAPISEK ROAD N° 191 54-57</xLgr><nro>19154</nro><xCpl>18TH FLOOR, CTI TOWE</xCpl><xBairro>KLONGTOEY</xBairro><cMun>9999999</cMun><xMun>EXTERIOR</xMun><UF>EX</UF><CEP>00010110</CEP><cPais>7765</cPais><xPais>TAILANDIA</xPais><fone>0</fone></enderDest><indIEDest>2</indIEDest></dest><det nItem="1"><prod><cProd>MAPWM387AA4</cProd><cEAN>SEM GTIN</cEAN><xProd>BTP M3*8L OD7 PW NI</xProd><NCM>73181500</NCM><CFOP>3101</CFOP><uCom>PC</uCom><qCom>250000</qCom><vUnCom>.04370764</vUnCom><vProd>10926.91</vProd><cEANTrib>SEM GTIN</cEANTrib><uTrib>KG</uTrib><qTrib>250000</qTrib><vUnTrib>.04370764</vUnTrib><indTot>1</indTot></prod><imposto><ICMS><ICMS40><orig>1</orig><CST>41</CST></ICMS40></ICMS><IPI><cEnq>335</cEnq><IPINT><CST>52</CST></IPINT></IPI><PIS><PISAliq><CST>02</CST><vBC>10926.91</vBC><pPIS>0.0000</pPIS><vPIS>0.00</vPIS></PISAliq></PIS><COFINS><COFINSAliq><CST>02</CST><vBC>10926.91</vBC><pCOFINS>0.0000</pCOFINS><vCOFINS>0.00</vCOFINS></COFINSAliq></COFINS></imposto></det><total><ICMSTot><vBC>0.00</vBC><vICMS>0.00</vICMS><vICMSDeson>0.00</vICMSDeson><vFCP>0.00</vFCP><vBCST>0.00</vBCST><vST>0.00</vST><vFCPST>0.00</vFCPST><vFCPSTRet>0.00</vFCPSTRet><vProd>10926.91</vProd><vFrete>0.00</vFrete><vSeg>0.00</vSeg><vDesc>0.00</vDesc><vII>0.00</vII><vIPI>0.00</vIPI><vIPIDevol>0.00</vIPIDevol><vPIS>0.00</vPIS><vCOFINS>0.00</vCOFINS><vOutro>0.00</vOutro><vNF>10926.91</vNF></ICMSTot></total><transp><modFrete>0</modFrete></transp><pag><detPag><tPag>90</tPag><vPag>0.00</vPag></detPag></pag><infAdic/><infRespTec><CNPJ>07200194000118</CNPJ><xContato>claudemir@gmail.com</xContato><email>claudemir@mail.com</email><fone>1155551122</fone></infRespTec></infNFe></NFe>';
       $file = '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe13200807200194000118550020000821421180953623" ';
       //echo $file;
        $arq = base64_encode($file );
        echo $arq;
        $msgRetorno = 0;
        $this->AbrirConexao();
        //$db->beginTransaction(); // Essential!
        try {
            $sql = " INSERT INTO mylobs ( mylob) VALUES ( $arq ) ";
            $stmt = $this->ora_conecta->prepare($sql);

            //$stmt->bindParam(':mylob', $file, \PDO::PARAM_LOB);

            $mylob = fopen("003799_10.xml", 'rb');
            echo '%%%%%%';
            echo $mylob;
            echo '%%%%%%';

            $stmt->execute();
            //$pdo->commit();
        } catch (PDOException $e) {
            echo "DataBase Error: The user could not be added.<br>".$e->getMessage();
        } catch (Exception $e) {
            echo "General Error: The user could not be added.<br>".$e->getMessage();
        }

        return $msgRetorno;
        
    }

    public function listNotasPendentes() {
        $sql = " SELECT * FROM VGE113_EMISSOR ";        
        $this->AbrirConexao();
        $stmt = $this->ora_conecta->prepare($sql);
        $stmt->execute();
        $linha = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->fecharConexao();
        return $linha;
    }

    public function selecionarNota($num_nota_fiscal, $cod_serie_nf) {
        $sql = " SELECT * FROM VGE113_EMISSOR WHERE NUM_NOTA_FISCAL = '" .$num_nota_fiscal ."' AND COD_SERIE_NF ='" .$cod_serie_nf ."'";        
        $this->AbrirConexao();
        $stmt = $this->ora_conecta->prepare($sql);
        $stmt->execute();
        $linha = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->fecharConexao();
        return $linha;
    }

    public function selecionarItens($num_nota_fiscal, $cod_serie_nf) {
        $sql = " SELECT * FROM VGE114_EMISSOR WHERE NUM_NOTA_FISCAL = '" .$num_nota_fiscal ."' AND COD_SERIE_NF ='" .$cod_serie_nf ."'";        
        $this->AbrirConexao();
        $stmt = $this->ora_conecta->prepare($sql);
        $stmt->execute();
        $linha = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->fecharConexao();
        return $linha;
    }    

    public function listaEmailPendentes() {        
        $sql = " SELECT A.* FROM GE113_EMAIL A WHERE A.STATUS = '0'" ;
        $this->AbrirConexao();
        $stmt = $this->ora_conecta->prepare($sql);
        $stmt->execute();
        $linha = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->fecharConexao();
        return $linha;
    }   

    public function saveNFe($img) {
        $sql = " INSERT INTO GE113NFETEST (NUM_RECIBO, NUM_NF_INTERNO) VALUES('cl','cla', .$img)" ;
        $this->AbrirConexao();
        $stmt = $this->ora_conecta->prepare($sql);
        $stmt->execute();
        //$linha = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->fecharConexao();
        //return $linha;
    }    

}