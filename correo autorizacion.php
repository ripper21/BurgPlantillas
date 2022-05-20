<?php

include("../../../burg/funciones.php");

$emails = $_GET['emails'];
$code = $_GET['ist'];

// variables del correo 

$para = $emails;
$subject = "BURG SpA | Infome Servicio Técnico N°" . $code;
$altbody="prueba";

$servername = "localhost";
$username = "root";
$password = "";
$database_name = "burgcl_forms"; 

$conn;
$group ="";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database_name;charset=utf8", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //  echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

$string =  "SELECT A.SVR_CUSTOM, A.SVR_GE_SERIAL, A.SVR_OBJ,  A.SVR_ALIAS, A.SVR_GE_TRD_MARK, A.SVR_GE_MODEL, A.SVR_ENG_TRD_M, A.SVR_ENG_SERIAL,  A.SVR_TECH_COMMENT, B.DESCRIPTION_NAME , A.SVR_TOTAL_TIME_EXP, A.SVR_AUTH_1, A.SVR_AUTH_2, JSON_UNQUOTE(JSON_EXTRACT(SVR_AST, '$.supNom')) as SVR_SUP_NAME, JSON_UNQUOTE(JSON_EXTRACT(SVR_AST, '$.supRut')) as SVR_SUP_RUT, SVR_OBJ ";

$string .= " FROM SVR A INNER JOIN DESCRIPTIONS B ON(IF(CAST(A.SVR_PROCEDURE AS UNSIGNED)=0,REVERSE(CAST(REVERSE(A.SVR_PROCEDURE) AS UNSIGNED)),CAST(A.SVR_PROCEDURE AS UNSIGNED)) = B.DESCRIPTION_ID) WHERE SVR_CODE = :code LIMIT 1";


if(isset($_REQUEST['userlog']) && isset($_REQUEST['ist']) ){

    $userlog = $_REQUEST['userlog'];
    $code    = $_REQUEST['ist'];

    $query = "UPDATE SVR SET SVR_AUTH_1 = 1 WHERE SVR_CODE = :code";
	$query2 = "UPDATE SVR SET SVR_AUTH_BY = '" . $_SESSION['User']."' WHERE SVR_CODE = :code";
    

    $stmt= $conn->prepare($query);
    $stmt2 =$conn->prepare($query2);


    $stmt->bindParam(":code" , $code);
	$stmt2->bindParam(":code" , $code);

   // $stmt->bindParam(":auth" , $auth);
   // checkeamos si están bien

    $stmt->execute();
	$stmt2->execute();

    $sucess = $stmt->rowCount();
    

  // checkeamos si están bien;

        $call = $conn->prepare($string);
        $call->bindParam(":code", $code );
        $call->execute();
        
        $row      = $call->fetch();

        $cliente     = $row['SVR_CUSTOM'];
        $ist         = $code;
        $mMarca      = $row['SVR_ENG_TRD_M'];
        $mSerie      = $row['SVR_ENG_SERIAL'];
        $servicio    = $row['DESCRIPTION_NAME'];
        $horas       = $row['SVR_TOTAL_TIME_EXP'];
        $alias       = $row['SVR_ALIAS'];
        $marca       = $row['SVR_GE_TRD_MARK'];
        $obs         = $row['SVR_TECH_COMMENT'];
        $modelo      = $row['SVR_GE_MODEL'];
        $serie       = $row['SVR_GE_SERIAL'];
        $userRut     = $row['SVR_SUP_RUT'];
        $userName    = $row['SVR_SUP_NAME'];
        $comentario  = ($row['SVR_TECH_COMMENT'] == "" || $row['SVR_TECH_COMMENT']  == null ) ? "Todo bien, ¡Felicitaciones!" : $row['SVR_TECH_COMMENT'];
        $tipoTrabajo = $row['DESCRIPTION_NAME'];

        $resume = 0;
        $variable = json_decode($row['SVR_OBJ'], true);
        $total = count($variable['values']) + 14 + 5; // 14 + por el prechecking + parametros + pst

          for($i=0 ; $i < count($variable['values']); $i++){
              if($variable['values'][$i]['obs']){ 
                   $resume += 1;
              }
          }

		  if($alias !== ""){
			  $alias = '"' . $alias . '"';
		  }


// fill the body 

$body = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="robots" content="noindex,nofollow">
	<meta name="og:description" content="Compartido via emBlue">
	<title>Correo 4 encuesta</title>
	<style type="text/css">

	.caja-roja {
		width: 154px;
		margin-bottom: 20px
	}
	.rrss2{width:430px;max-width:430px;padding-left:10px}
	.rrss3{display:none !important;}
	@media only screen and (max-width:480px) {

    .partner-parent{
      padding-left: 15px !important;
    }

		table[class~=text-block] {
			display: block!important
		}
		table[class~=".ep-struct-content-width"] {
			width: 100%!important;
			min-width: 100%!important;
			max-width: 100%!important
		}
		table[class~=".ep-struct-content"] {
			width: 100%!important;
			min-width: 100%!important;
			max-width: 100%!important
		}
		table[class~=".ep-col-mobile"] {
			width: 100%!important;
			max-width: 100%!important
		}
		table[class~=".ep-struct-content"] img {
			max-width: 480px;
			width: 100%!important;
			height: auto!important
		}
		img[class~=fullWidthImg] {
			width: 100%!important;
			height: auto!important
		}
		td[class~=".ep-title1"] {
			font-size: 20px!important;
			line-height: 28px!important;
			text-align: left!important
		}
		td[class~=".ep-text1"] {
			font-size: 12px!important;
			text-align: justify!important;
			line-height: 1px!important
		}
		td[class~=".ep-title2"] {
			font-size: 16px!important;
			line-height: 22px!important;
			text-align: left!important
		}
		td[class~=".ep-text2"] {
			font-size: 14px!important;
			line-height: 22px!important;
			text-align: center!important
		}
		td[class~=".ep-title3"] {
			font-size: 14px!important;
			line-height: 18px!important;
			text-align: left!important
		}
		td[class~=".ep-text3"] {
			font-size: 12px!important;
			line-height: 20px!important;
			text-align: center!important
		}
		.datos {
			font-size: 1.1em !important
		}
		.ep-block {
			padding-left: 0!important
		}
		.texto-nuevo {
			font-size: 70px!important
		}
		.caja-roja {
			width: 410px !important;
			padding: 30px 14px 15px!important
		}
		.nombre {
			position: relative;
			top: -20px!important
		}
		.cargo {
			font-size: 29px!important;
			position: relative;
			top: -18px
		}
		.telefono {
			font-size: 36px!important;
			position: relative;
			top: -18px!important
		}
		.email {
			font-size: 35px!important;
			position: relative
		}
		.observaciones{
			padding-left:10px !important;
		}
		.variables{
			padding-right:30px !important;
		}
		.ep-resp-img{
			width: 100% !important;
		}
		.foto{ display: block !important; width: 100% !important}
		.camilo{padding-top:30px !important}
		.rrss3{display:block !important;}
		.rrss2{max-width:100% !important;width: auto !important;display:none !important}
		.equipo0{
            font-size:14px !important;
		}
		.equipo1{
            font-size:18px !important;
		}
		.servicio_realizado, .tu_equipo_presenta, .observaciones{
			font-size: 16px !important;
		}
	}
	
	@media only screen and (min-width:600px) {
		.foto {
			max-width: 197px!important
		}
		.caja-roja {
			display: inline-block
		}
	}
	
	p span {
		position: relative;
		top: -8px
	}
	
	a {
		text-decoration: none;
		outline: 0;
		color: inherit!important
	}
	
	a:active,
	a:link {
		text-decoration: none;
		outline: 0;
		color: #fff!important
	}


	</style>
</head>
<body style="background-color: #FFFFFF; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
  <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF;" width="100%">
    <tbody>
      <tr>
        <td>
          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
                    <tbody>
                      <tr>
                        <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                          <table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                            <tr>
                              <td style="width:100%;padding-right:0px;padding-left:0px;">
                                <div align="center" style="line-height:10px">
                                  <img class="big" src="https://files.embluemail.com/uo/34234/logoooooo.jpg" style="display: block; height: auto; border: 0; width: 500px; max-width: 100%;" width="500" />
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
                    <tbody>
                      <tr>
                        <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="50%">
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:15px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                <div class="servicio_realizado" style="color:#000000;font-size:14px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:700;line-height:120%;text-align:center;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">SERVICIO REALIZADO</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="10" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td>
                                <div class="tipo_de_trabajo" style="color:#000000;font-size:14px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:center;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">' . $tipoTrabajo . '</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:10px;padding-right:10px;padding-bottom:15px;padding-left:10px;">
                                <div class="alias" style="color:#000000;font-size:14px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:700;line-height:120%;text-align:center;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">' .$alias . '</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                        <td class="column column-2" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="50%">
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:15px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                <div class="tu_equipo_presento" style="color:#000000;font-size:14px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:700;line-height:120%;text-align:center;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">TU EQUIPO PRESENTÓ</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td>
                                <div style="color:#bb0000;font-size:70px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:700;line-height:120%;text-align:center;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">' . $resume . '</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-right:10px;padding-bottom:15px;padding-left:10px;">
                                <div class="observaciones" style="color:#000000;font-size:14px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:700;line-height:120%;text-align:center;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">Observaciones</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e6e6e6; color: #000000; width: 500px;" width="500">
                    <tbody>
                      <tr>
                        <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="50%">
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-left:5px">
                                <div style="color:#000000;font-size:10px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">Marca del generador.</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-right:5px;padding-bottom:10px;padding-left:5px;">
                                <div style="color:#000000;font-size:16px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;"> '  . $marca  . ' </p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                        <td class="column column-2" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="50%">
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-left:5px">
                                <div style="color:#000000;font-size:10px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">Número de Serie del Generador</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-right:5px;padding-bottom:10px;padding-left:5px;">
                                <div style="color:#000000;font-size:16px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">' . $serie  . '.</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>



          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e6e6e6; color: #000000; width: 500px;" width="500">
                    <tbody>
                      <tr>
                        <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; background-color: #e6e6e6; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="50%">
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-left:5px">
                                <div style="color:#000000;font-size:10px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">Marca del Motor</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-right:5px;padding-bottom:10px;padding-left:5px;">
                                <div style="color:#000000;font-size:16px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">' . $mMarca . '.</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                        <td class="column column-2" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="50%">
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-left:5px">
                                <div style="color:#000000;font-size:10px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">Número de serie del Motor</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td style="padding-top:5px;padding-right:5px;padding-bottom:10px;padding-left:5px;">
                                <div style="color:#000000;font-size:16px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-weight:400;line-height:120%;text-align:left;direction:ltr;letter-spacing:0px;">
                                  <p style="margin: 0;">' . $mSerie . '</p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>


          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;padding-top:16px" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
                    <tbody>
                      <tr>
                        <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                          <table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                            <tr>
                              <td style="width:100%;padding-right:0px;padding-left:0px;">
                                <div align="center" style="line-height:10px">
                                <a href="https://burgnet.burg.cl/burg/form/Informe.php?o=' . $ist . '" target="_blank" > <img src="https://i.ibb.co/FnXY6Cj/banner-correo.png" style="display: block; height: auto; border: 0; width: 500px; max-width: 100%;" width="500" /></a>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <table class="ep-struct-content ep-struct" cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin: auto;max-width: 600px; width: 100%; background-color: rgba(255, 255, 255, 0);" align="center" valign="top">
  <tbody>
	 <tr>
		<td valign="top" align="center" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
		   <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="ep-col-mobile" style="border-collapse: collapse;">
			  <tbody>
				 <tr>
					<td class="" style="background-color:transparent;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
					   <table cellpadding="0" cellspacing="0" border="0" width="100%">
						  <tbody>
							 <tr>
								<td valign="top" align="left" class="ep-text1" style="font-size:14px;font-family:Arial;line-height:12px;font-style:normal;font-weight:normal;text-decoration:none;text-align:justify;color:rgb(68, 68, 68);padding:10px 0px;padding-top:18px;width:600px">
								   <div style="text-align:center;line-height:inherit;"><span class="equipo0" style="font-size:18px;line-height:inherit;"><span style="color:#000000;line-height:inherit;"><strong>¿CONSULTAS? LLAMA A NUESTRO</strong></span></span></div>
								</td>
							 </tr>
							 <tr>
								<td valign="top" align="left" class="ep-text1" style="font-size:14px;font-family:Arial;line-height:12px;font-style:normal;font-weight:normal;text-decoration:none;text-align:justify;color:rgb(68, 68, 68);padding:10px 0px;background-color:#000000;padding-top:14px;padding-bottom:10px;width:600px">
								   <div style="text-align:center;line-height:inherit;"><span style="color:#FFFFFF;line-height:inherit;"><span class="equipo1" style="font-size:24px;line-height:1.2;"><strong>EQUIPO DE SERVICIO TÉCNICO</strong></span></span><span style="color:#000000;line-height:inherit;"><span style="font-size:20px;line-height:inherit;"></span></span></div>
								</td>
							 </tr>
						  </tbody>
					   </table>
					</td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>


  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
  <tbody>
	 <tr>
		<td>
		   <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
			  <tbody>
				 <tr>
					<td class="column column-1 foto camilo" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><img alt="Imagen" border="0" class="ep-resp-img" src="https://i.ibb.co/Lv4CF34/foto-jerson-1-1.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0!important" width="177"></td>
					<td class="column column-2 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><a href="mailto:jb@burg.cl"><img alt="Imagen" border="0" class="ep-resp-img" src="https://i.ibb.co/dpwMKct/jb.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0!important" width="177"></a></td>
					<td class="column column-3 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><img alt="Imagen" border="0" class="ep-resp-img" src="https://files.embluemail.com/uo/34234/coordinador_de_servicios.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0 !important" width="177"></td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
  <tbody>
	 <tr>
		<td>
		   <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
			  <tbody>
				 <tr>
					<td class="column column-1 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><img alt="Imagen" border="0" class="ep-resp-img" src="https://files.embluemail.com/uo/34234/foto_rosa.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0!important" width="177"></td>
					<td class="column column-2 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><a href="mailto:prevencionista@burg.cl"><img alt="Imagen" border="0" class="ep-resp-img" src="https://files.embluemail.com/uo/34234/nombre_rosa.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0!important" width="177"></a></td>
					<td class="column column-3 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><img alt="Imagen" border="0" class="ep-resp-img" src="https://files.embluemail.com/uo/34234/nuestra_prevencionista.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0 !important" width="177"></td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
  <tbody>
	 <tr>
		<td>
		   <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
			  <tbody>
				 <tr>
					<td class="column column-1 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><img alt="Imagen" border="0" class="ep-resp-img" src="https://files.embluemail.com/uo/34234/foto_camilo.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0!important" width="177"></td>
					<td class="column column-2 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><a href="mailto:ca@burg.cl"><img alt="Imagen" border="0" class="ep-resp-img" src="https://i.ibb.co/W602GTJ/CA-copia.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0!important" width="177"></a></td>
					<td class="column column-3 foto" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 5px solid #FFF; border-left: 0px;padding-top:15px" width="33.333333333333336%"><img alt="Imagen" border="0" class="ep-resp-img" src="https://files.embluemail.com/uo/34234/encargado_del_area.jpg" style="border:0;outline:0;text-decoration:none;vertical-align:bottom;width:100%;display:block;max-width:519px;min-width:0 !important" width="177"></td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>
<table class="ep-struct-content ep-struct" cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin: auto;max-width: 600px; width: 100%; background-color: rgba(255, 255, 255, 0);" align="center" valign="top">
  <tbody>
	 <tr>
		<td valign="top" align="center" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
		   <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="ep-col-mobile" style="border-collapse: collapse;">
			  <tbody>
				 <tr>
					<td class="" style="background-color:transparent;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
					   <table cellpadding="0" cellspacing="0" border="0" width="100%">
						  <tbody>
							 <tr class="tr_imgEditable">
								<td align="center" class="" style="padding:5px;background-color:transparent;padding-top:0px;padding-bottom:0px;"> <img src="https://i.postimg.cc/YqzP2ZqJ/marcas-2.jpg" class="editable fullWidthImg ep-resp-img" border="0" alt="Imagen" style="width:100%;display:block;border:none;background-color:rgba(0, 0, 0, 0);max-width:600px;min-width:0px !important;" width="580" title=""> </td>
							 </tr>
						  </tbody>
					   </table>
					</td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>
<table class="ep-struct-content ep-struct rrss2" cellpadding="0" width="430" cellspacing="0" style="width: 430px; table-layout: fixed; margin: auto; background-color: rgba(255, 255, 255, 0);" align="center" valign="top">
  <tbody>
	 <tr>
		<td valign="top" align="center" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
		   <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="ep-col-mobile" style="border-collapse: collapse;">
			  <tbody>
				 <tr>
					<td class="" style="background-color:transparent;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
					   <table cellpadding="0" cellspacing="0" border="0" width="100%">
						  <tbody>
							 <tr class="tr_imgEditable">
								<td align="center" class="" style="padding:5px;background-color:transparent;padding-top:0px;padding-bottom:0px;"> <img usemap="#Map" src="http://burgnet.burg.cl/burg/images_email/email_link2.jpg"" class="editable ep-resp-img" border="0" alt="Imagen" style="display:block;border:none;background-color:rgba(0, 0, 0, 0);max-width:600px;min-width:0px !important;" width="430" title=""> </td>
							 </tr>
						  </tbody>
					   </table>
					</td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>


<table align=""class="ep-struct-content ep-struct" cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin: auto;max-width: 600px; width: 100%; background-color: rgba(255, 255, 255, 0);" align="center" valign="top">
  <tbody>
	 <tr>
		<td valign="top" align="center" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
		   <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="ep-col-mobile" style="border-collapse: collapse;">
			  <tbody>
				 <tr>
					<td class="" style="background-color:transparent;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
					   <table cellpadding="0" cellspacing="0" border="0" width="100%">
						  <tbody>
							 <tr class="tr_imgEditable">
								<td align="center" class="" style="padding:5px;background-color:transparent;padding-bottom:0px;padding-top:0px;">
								   <a href="https://burg.cl"> <img src="https://i.ibb.co/Dz0DFKp/Captura-de-pantalla-2022-05-10-a-la-s-15-56-15-1.png" class="editable  ep-resp-img" border="0" alt="Imagen" style="width:50%;display:block;border:none;background-color:rgba(0, 0, 0, 0);max-width:300px;min-width:0px !important;" width="300" title=""> </a>
								</td>
							 </tr>
						  </tbody>
					   </table>
					</td>
				 </tr>
			  </tbody>
		   </table>
		</td>
	 </tr>
  </tbody>
</table>

<map name="Map">
  <area shape="rect" coords="2,3,80,28" href="https://www.linkedin.com/company/71091350">
  <area shape="rect" coords="85,4,190,27" href="https://www.facebook.com/burgSpA">
  <area shape="rect" coords="200,1,325,26" href="https://www.instagram.com/burg_spa_grupos_electrogenos/?hl=es">
  <area shape="rect" coords="330,3,410,27" href="https://www.youtube.com/channel/UCddXSKjZayy5XKkm_JyPsoA">
</map>
<map name="Map2"><!-- con 100% -->
  <area shape="rect" coords="2,3,115,28" href="https://www.linkedin.com/company/71091350">
  <area shape="rect" coords="120,4,200,27" href="https://www.facebook.com/burgSpA">
  <area shape="rect" coords="210,1,280,26" href="https://www.instagram.com/burg_spa_grupos_electrogenos/?hl=es">
  <area shape="rect" coords="290,3,350,27" href="https://www.youtube.com/channel/UCddXSKjZayy5XKkm_JyPsoA">
</map>

	  <!-- End -->
  </body>

';


           $react = mail_enviar($subject, $emails , $body );

          echo $react;
      

} 




?>
