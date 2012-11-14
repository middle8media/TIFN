<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "vice-president@triadindie.org" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "20110729-face" );

?>
<?php
/**
 * Copyright (C) : http://www.formmail-maker.com
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    $_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'E563' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNEQxmA0AFJLKBBpIHR0dEhAE2MtcEBSKKIhbCCaYT7QqOmLl06ddXSLCT3AeUbXR0dGlDNA4qBTUUxD4sYayu6W0JDGEPQ3TxQ4UdFiMV9AI1GzinWIHKtAAAAAElFTkSuQmCC',
			'BC12' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMEx1QBILmMLa6BDCEBCALNYq0uAYwugggqIOyJvC0CCC5L7QqGmrVgFRFJL7oOoaHdDMA4q1MqCJOUwBmYjmlikMAehuZgx1DA0ZBOFHRYjFfQB3984GORchFAAAAABJRU5ErkJggg==',
			'E0B2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMYAlhDGaY6IIkFNDCGsDY6BASgiLG2sjYEOoigiIk0ujY6NIgguS80atrK1NBVq6KQ3AdV1+iArrchoJUBw46AKQxY3ILpZsbQkEEQflSEWNwHAHo0zf1pJxI8AAAAAElFTkSuQmCC',
			'383F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7RAMYQxhDGUNDkMQCprC2sjY6OqCobBVpdGgIRBUDqmNAqAM7aWXUyrBVU1eGZiG7D1UdbvOwiGFzC9TNqHoHKPyoCLG4DwAWxMpqJ1QhSwAAAABJRU5ErkJggg==',
			'6D2A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCGVqRxUSmiLQyOjpMdUASC2gRaXRtCAgIQBZrEGl0aAh0EEFyX2TUtJVZKzOzpiG5L2QKUF0rI0wdRG8rUGwKY2gIulgAqjqwWxxQxUBuZg0NRBEbqPCjIsTiPgB66cwzNpoRSgAAAABJRU5ErkJggg==',
			'B293' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUIdkMQCprC2Mjo6OgQgi7WKNLo2BDSIoKhjAIsFILkvNGrV0pWZUUuzkNwHVDeFIQSuDmoeQwADunmtjA6MGHawNqC7JTRANNQBzc0DFX5UhFjcBwC2Zc4rZUFq/AAAAABJRU5ErkJggg==',
			'4454' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37pjC0soY6NAQgi4UwTGVtYGhEFmMMYQgFirUii7FOYXRlncowJQDJfdOmLV26NDMrKgrJfQFTRFoZGgIdkPWGhooCbQ0MDUF3C9AlAWhijI4OGGIMoQyoYgMVftSDWNwHACczzSPBeYDjAAAAAElFTkSuQmCC',
			'D78B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGUMdkMQCpjA0Ojo6OgQgi7UyNLo2BDqIoIq1MiLUgZ0UtXTVtFWhK0OzkNwHVBfAiGEeowMrhnmsDRhiU0Qa0PWGBgBVoLl5oMKPihCL+wAFyMy7u+APqgAAAABJRU5ErkJggg==',
			'AF05' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMIYGIImxBog0MIQyOiCrE5ki0sDo6IgiFtAq0sDaEOjqgOS+qKVTw5auioyKQnIfRF1AgwiS3tBQTDGQOpAd6GIMoQwBAehiUximOgyC8KMixOI+AECvy+FwtSnDAAAAAElFTkSuQmCC',
			'809F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUNDkMREpjCGMDo6OiCrC2hlbWVtCEQRE5ki0uiKEAM7aWnUtJWZmZGhWUjuA6lzCAlEMw8o1oAuxtrKiGEHplugbkYRG6jwoyLE4j4A1EPJaF3KHL4AAAAASUVORK5CYII=',
			'8176' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA6Y6IImJTGEMYGgICAhAEgtoBapsCHQQQFHHEMDQ6OiA7L6lUauiVi1dmZqF5D6wOqCZqOYBxQIYHUTQxBgdUMVAelkbGFD0sgJdDBRDcfNAhR8VIRb3AQBffsm/amXD8AAAAABJRU5ErkJggg==',
			'0ED8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDGaY6IImxBog0sDY6BAQgiYlMAYo1BDqIIIkFtILEAmDqwE6KWjo1bOmqqKlZSO5DU4ckhmoeNjuwuQWbmwcq/KgIsbgPAElSzEoQcM+mAAAAAElFTkSuQmCC',
			'1882' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaY6IImxOrC2Mjo6BAQgiYk6iDS6NgQ6iKDoBatrEEFy38qslWGrQletikJyH1RdowOKXpB5Aa0MmGJTGDDtCEAWEw0BuZkxNGQQhB8VIRb3AQB+j8lHWcTX5QAAAABJRU5ErkJggg==',
			'EC83' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7QkMYQxmA0AFJLKCBtdHR0dEhAEVMpMEVTKKKMTo6NAQguS80ahqQWLU0C8l9aOrgYqxYzMO0A9Mt2Nw8UOFHRYjFfQDs/c5p8Fw74QAAAABJRU5ErkJggg==',
			'6976' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA6Y6IImJTGFtZWgICAhAEgtoEWl0aAh0EEAWawCKNTo6ILsvMmrp0qylK1OzkNwXMoUx0GEKI6p5rQyNDgGMDiIoYixA01DFQG5hbWBA0Qt2cwMDipsHKvyoCLG4DwDXpcyAbj+I4AAAAABJRU5ErkJggg==',
			'9394' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANYQxhCGRoCkMREpoi0Mjo6NCKLBbQyNLoCSTSxVtaGgCkBSO6bNnVV2MrMqKgoJPexujK0MoQEOiDrBYo0OjQEhoYgiQkAxRyBLsHiFhQxbG4eqPCjIsTiPgDKqc1x+3uN+wAAAABJRU5ErkJggg==',
			'6632' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nM2QwQnAMAhFzSEb2H0cwUJSaKYxUDdIOoRTNkchObZQ/+k/BB+CTSPwp3ziFzmkkKGTY9iixkrMjvGFFWQn9ExGqyTo/M5yH9bNivNLbdOxV/0NVhydFWbWYOEyO4ecfvC/F7PwewDa1c2OW5WjNAAAAABJRU5ErkJggg==',
			'EAE4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHRoCkMQCGhhDWBsYGlHFWFuBYq2oYiKNrg0MUwKQ3BcaNW1lauiqqCgk90HUMTqg6hUNBYqFhmCa14DFDhSx0BCgGJqbByr8qAixuA8AjfrPMd9YM1UAAAAASUVORK5CYII=',
			'AE0F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMIaGIImxBog0MIQyOiCrE5ki0sDo6IgiFtAq0sDaEAgTAzspaunUsKWrIkOzkNyHpg4MQ0MxxUDqsNmB7paAVrCbUcQGKvyoCLG4DwC3pMmUaPfcUgAAAABJRU5ErkJggg==',
			'9C72' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDA6Y6IImJTGFtdGgICAhAEgtoFWlwaAh0EEETYwCqFEFy37Sp01atWrpqVRSS+1hdgSqmgFQi2QzSG8DQiuwWAaCYowNQJZpbXEEq0d3cwBgaMgjCj4oQi/sAZEXMw6rGrq0AAAAASUVORK5CYII=',
			'7999' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGaY6IIu2srYyOjoEBKCIiTS6NgQ6iCCLTUERg7gpaunSzMyoqDAk9zE6MAY6hARMRdbL2sDQ6NAQ0IAsJtLA0ujYEIBiR0ADplsCGrC4eYDCj4oQi/sAvhPL+fIRJdsAAAAASUVORK5CYII=',
			'A323' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGUIdkMRYA0RaGR0dHQKQxESmMDS6NgQ0iCCJBbQytALJhgAk90UtXRW2amXW0iwk94HVgVUi9IaGMjQ6TGFAN6/RIQBdDOgWB0YUtwS0soawhgaguHmgwo+KEIv7AKoVzNDgPlZfAAAAAElFTkSuQmCC',
			'C555' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WENEQ1lDHUMDkMREWkUaWBsYHZDVBTRiEWsQCWGdyujqgOS+qFVTly7NzIyKQnIf0OxGB5BqFL1YxBpFGl0bAh1EUNzC2sro6BCA7D7WEMYQhlCGqQ6DIPyoCLG4DwC0eswE06eYuQAAAABJRU5ErkJggg==',
			'99C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCHaY6IImJTGFtZXQICAhAEgtoFWl0bRB0EMAQY3RAdt+0qUuXpq5amZqF5D5WV8ZAoDoU8xhaGcB6RZDEBFpZwHaIEHALNjcPVPhREWJxHwCOOcubL0SKnQAAAABJRU5ErkJggg==',
			'B510' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgNEQxmmMLQiiwVMEWlgCGGY6oAs1irSwBjCEBCAqi6EYQqjgwiS+0Kjpi5dNW1l1jQk9wVMYWh0QKiDmodNTAQohm4HayvQfShuCQ1gDGEMdUBx80CFHxUhFvcBAG9EzVAB+vxIAAAAAElFTkSuQmCC',
			'6307' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANYQximMIaGIImJTBFpZQgF0khiAS0MjY6ODqhiDQytrEAyAMl9kVGrwpauilqZheS+kClgda3I9gJ5ja4NAVPQxYB2BDBguIXRAYubUcQGKvyoCLG4DwAu38v9qHPBzwAAAABJRU5ErkJggg==',
			'781B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkMZQximMIY6IIu2srYyhDA6BKCIiTQ6AsVEkMWmANVNgauDuClqZdiqaStDs5Dcx+iAog4MWRtEGh2moJongkUsoAFTb0ADYwhjqCOqmwco/KgIsbgPAAv3yq+84u82AAAAAElFTkSuQmCC',
			'01BF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUNDkMRYAxgDWBsdHZDViUxhDWBtCEQRC2hlQFYHdlLUUiAKXRmaheQ+NHUIMTTzRKZgirEGYOpldGANBboZRWygwo+KEIv7AHsPx4ghx+43AAAAAElFTkSuQmCC',
			'7151' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZAlhDHVpRRFsZA1gbGKaiirGCxEJRxKYA9U5lgOmFuClqVdTSzKylyO5jdGAIYGgIQLEDaBaGmAhQjBVNLAAoxujogCbGGgp0SWjAIAg/KkIs7gMAj0zJc9yJgWwAAAAASUVORK5CYII=',
			'C745' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WENEQx0aHUMDkMREWhkaHVodHZDVBTQCxaaiiTUwtDIEOro6ILkvatWqaSszM6OikNwHVBfA2ujQIIKil9GBFWgrilgjawNDo6ODCIpbgLxGhwBk97GGgMWmOgyC8KMixOI+AAClzOLrVk70AAAAAElFTkSuQmCC',
			'691C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQximMEwNQBITmcLayhDCECCCJBbQItLoGMLowIIs1iDS6DCF0QHZfZFRS5dmTVuZhey+kCmMgUjqIHpbGRoxxVjAYsh2gN0yBdUtIDczhjqguHmgwo+KEIv7APkGy0sLSVsmAAAAAElFTkSuQmCC',
			'F161' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGVqRxQIaGAMYHR2mooqxBrA2OISiijEAxeB6wU4KjVoVtXTqqqXI7gOrc3RoxdQbQJQYI4Ze1lCgm0MDBkH4URFicR8AnZ3LEFgD2GQAAAAASUVORK5CYII=',
			'0D3D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7GB1EQxhDGUMdkMRYA0RaWRsdHQKQxESmiDQ6NAQ6iCCJBbQCxYDqRJDcF7V02sqsqSuzpiG5D00dQgzNPGx2YHMLNjcPVPhREWJxHwCDNsx6S8FoZAAAAABJRU5ErkJggg==',
			'5E06' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMEx1QBILaBBpYAhlCAhAE2N0dHQQQBILDBBpYG0IdEB2X9i0qWFLV0WmZiG7rxWsDsU8qJiDCLIdrRA7kMVEpmC6hTUA080DFX5UhFjcBwBEIstbo4C0AAAAAABJRU5ErkJggg==',
			'06DB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGUMdkMRYA1hbWRsdHQKQxESmiDSyNgQ6iCCJBbSKNIDEApDcF7V0WtjSVZGhWUjuC2gVbUVSB9Pb6IpmHsgOdDFsbsHm5oEKPypCLO4DAFj7y5srEBOwAAAAAElFTkSuQmCC',
			'1410' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YWhmmADGSGKsDw1SGEIapDkhiog4MoYwhDAEBKHoZXRmmMDqIILlvZdbSpaumrcyahuQ+oIpWJHVQMdFQBwwxsFvQ7ACLobolhKGVMdQBxc0DFX5UhFjcBwAWuchL+3bLJgAAAABJRU5ErkJggg==',
			'AB53' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHUIdkMRYA0RaWYEyAUhiIlNEGl1BNJJYQCtQ3VQgjeS+qKVTw5ZmZi3NQnIfSB1IFbJ5oaEijQ5AETTzgHZgiLUyOjqiuCWgVTSEIZQBxc0DFX5UhFjcBwCuVs20pJ4jogAAAABJRU5ErkJggg==',
			'5E73' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDA0IdkMQCGkSAZKBDAIYYhITBwAAgr9GhIQDJfWHTpoatWrpqaRay+1qB6qYwNCCbBxYLYEAxLwAoxuiAKiYyRaSBFSiKrJc1AOjmBgYUNw9U+FERYnEfAEg5zLJ+NxhUAAAAAElFTkSuQmCC',
			'2527' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nM2QsQ0DMQhFofAGZB+yASeZJTwFV7CBLxuksKc8SqxceVHC7x5f4gmYH2PwT/mKX5GHgqLWxKiT4ZONEhMnKyYLA6cam0jyex3vOdpo2U9gZwfPd5GDdeiLi9HOEu3sYsWRo52YKtai28J+9b8bc+F3As5iys0jjMKoAAAAAElFTkSuQmCC',
			'1F5F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ11DHUNDkMRYHUQaWIEyyOpEsYgxgsSmwsXATlqZNTVsaWZmaBaS+0DqGBoCMfRiE2PFIsbo6IjqlhCg3lBUtwxU+FERYnEfAEWqxo6KAtzJAAAAAElFTkSuQmCC',
			'ED4B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNEQxgaHUMdkMQCGkRaGVodHQJQxRodpjo6iKCLBcLVgZ0UGjVtZWZmZmgWkvtA6lwbMc1zDQ3ENK8Rw45WBjS92Nw8UOFHRYjFfQD7Js5w/BM0DQAAAABJRU5ErkJggg==',
			'652D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WANEQxlCGUMdkMREpog0MDo6OgQgiQW0iDSwNgQ6iCCLNYiEMCDEwE6KjJq6dNXKzKxpSO4LmcLQ6NDKiKq3FSg2BV1MpNEhAFVMZAorUCcjiltYAxhDWEMDUdw8UOFHRYjFfQDaSssbK+xM2AAAAABJRU5ErkJggg==',
			'B0D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgMYAlhDGVqRxQKmMIawNjpMdUAWa2VtZW0ICAhAUSfS6NoQ6CCC5L7QqGkrU1dFZk1Dch+aOqh52MSw2YHpFmxuHqjwoyLE4j4AqzLOM1xcDlIAAAAASUVORK5CYII=',
			'F22C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGaYGIIkFNLC2Mjo6BIigiIk0ujYEOrCgiDE0OgDFkN0XGrVq6aqVmVnI7gOqm8LQyujAgKo3gGEKuhiQH8CIZgcrWBTVLaKhrqEBKG4eqPCjIsTiPgC5d8u72kx9wQAAAABJRU5ErkJggg==',
			'0673' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0IdkMRYA1hbGRoCHQKQxESmiDQyNAQ0iCCJBbQCeY0ODQFI7otaOi1s1dJVS7OQ3BfQKtrKMIWhIQBVb6NDAAOKeSA7HB1QxUBuYQW6Elkv2M0NDChuHqjwoyLE4j4AfIvMRksyI+YAAAAASUVORK5CYII=',
			'60CA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhCHVqRxUSmMIYwOgRMdUASC2hhbWVtEAgIQBZrEGl0bWB0EEFyX2TUtJWpq1ZmTUNyX8gUFHUQva1gsdAQFDGQHYIo6iBuCUQRg7jZEUVsoMKPihCL+wBPl8tUTW3hZwAAAABJRU5ErkJggg==',
			'9C2E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxlCGUMDkMREprA2Ojo6OiCrC2gVaXBtCMQQY0CIgZ00beq0VatWZoZmIbmP1RWorpURRS8DSO8UVDEBoJhDAKoY2C0OqGIgN7OGBqK4eaDCj4oQi/sAT0LJuK7FWbUAAAAASUVORK5CYII=',
			'DA03' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYAhimMIQ6IIkFTGEMYQhldAhAFmtlbWV0dGgQQRETaXRtCGgIQHJf1NJpK1OBZBaS+9DUQcVEQ0Fi6OY5otsxRaTRAc0toQFAMTQ3D1T4URFicR8Anr3PO2LxZFsAAAAASUVORK5CYII=',
			'6563' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQxmA0AFJTGSKSAOjo6NDAJJYQItIA2uDQ4MIsliDSAgrmEa4LzJq6tKlU1ctzUJyX8gUhkZXR4cGFPNagWIgE1DERDDERKawtqK7hTWAMQTdzQMVflSEWNwHAIatzVsQxgVyAAAAAElFTkSuQmCC',
			'D2FB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA0MdkMQCprC2sjYwOgQgi7WKNLoCxURQxBjAYgFI7otaumrp0tCVoVlI7gOqm4JpHkMAK4Z5jA4YYkCd6HpDA0RDgfaiuHmgwo+KEIv7AGgvzEAdjsBZAAAAAElFTkSuQmCC',
			'F4C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMZWhlCHVqRxQIaGKYyOgRMRRMLZW0QCEUVY3RlbWCA6QU7KTRq6dKlq1YtRXZfQINIK5I6qJhoqCuGGANQnQCGGNAtGGJAN4cGDILwoyLE4j4A9XrM7xHMtkcAAAAASUVORK5CYII=',
			'28BF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUNDkMREprC2sjY6OiCrC2gVaXRtCEQRY2hFUQdx07SVYUtDV4ZmIbsvANM8RgdM81gbMMVEGjD1hoaC3YzqlgEKPypCLO4DAG8zyeVUuvomAAAAAElFTkSuQmCC',
			'FD17' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkNFQximMIaGIIkFNIi0MoQwNIigijU6YhFzmAKiEe4LjZq2MmvaqpVZSO6DqmtlwNQ7BYtYAAO6W6YwOqCKiYYwhjqiiA1U+FERYnEfAAzszZFIjxONAAAAAElFTkSuQmCC',
			'1171' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDA1qRxVgdGAMYGgKmIouJOrCCxELR9TI0OsD0gp20MmtV1KqlQIjkPrC6KQytGHoDMMWAGEOMtQFVTDSENRQoFhowCMKPihCL+wDYUsb6+wYy4wAAAABJRU5ErkJggg==',
			'E052' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHaY6IIkFNDCGsDYwBASgiLG2sjYwOoigiIk0uk5laBBBcl9o1LSVqZlZq6KQ3AdS59AQ0OiAphco1sqAYUfAFAY0tzA6OgSgu5khlDE0ZBCEHxUhFvcBAFZFzRC2ycocAAAAAElFTkSuQmCC',
			'FCF2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMZQ1lDA6Y6IIkFNLA2ujYwBASgiIk0uDYwOoigibECaREk94VGTVu1NHTVqigk90HVNTpg6m1lwLCDYQoDFregigHdDMQhgyD8qAixuA8AIi7NrCx4YHIAAAAASUVORK5CYII=',
			'BA21' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGVqRxQKmMIYwOjpMRRFrZW1lbQgIRVUn0ugAlEF2X2jUtJVZK7OWIrsPrK4VzY5W0VCHKehiQHUB6G4RaXR0QBULDRBpdA0NCA0YBOFHRYjFfQDxwM3dJ8z4xAAAAABJRU5ErkJggg==',
			'5969' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGaY6IIkFNLC2Mjo6BASgiIk0ujY4OoggiQUGgMQYYWJgJ4VNW7o0deqqqDBk97UyBro6OkxF1svQygDUCzQV2Y5WFpAYih0iUzDdwhqA6eaBCj8qQizuAwCMz8xoy5tkJAAAAABJRU5ErkJggg==',
			'024D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHUMdkMRYA1hbGVodHQKQxESmiDQ6THV0EEESC2hlaHQIhIuBnRS1dNXSlZmZWdOQ3AdUN4W1EUNvAGtoIIqYyBRGBwY0dUC3NIDEkN3C6CAa6oDm5oEKPypCLO4DAEVny24BjJ52AAAAAElFTkSuQmCC',
			'A94A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHVqRxVgDWFsZWh2mOiCJiUwRaQSKBAQgiQW0AsUCHR1EkNwXtXTp0szMzKxpSO4LaGUMdG2EqwPD0FCGRtfQwNAQFPNYGh3Q1AW0At2CIQZyM6rYQIUfFSEW9wEAcYrNTDOHNBkAAAAASUVORK5CYII=',
			'44B7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpI37pjC0soYyhoYgi4UwTGVtdGgQQRJjDGEIZW0IQBFjncLoClIXgOS+adOWLl0aumplFpL7AqaItALVtSLbGxoqGuoKlMFwS0NAAIZYo6MDFjejig1U+FEPYnEfAAAWy/ffdVaIAAAAAElFTkSuQmCC',
			'375A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7RANEQ11DHVqRxQKmMDS6NjBMdUBW2QoWCwhAFpvC0Mo6ldFBBMl9K6NWTVuamZk1Ddl9UxgCGBoCYeqg5jE6AMVCQ1DEWBtY0dQFTBFpYHR0RBETDQDyQhlRzRug8KMixOI+ADDjyxSVg2jeAAAAAElFTkSuQmCC',
			'775B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkNFQ11DHUMdkEVbGRpdGxgdArCIiSCLTWFoZZ0KVwdxU9SqaUszM0OzkNzH6MAQwNAQiGIeK0gUKIZsnghQlBVNLAAoyujoiKIXJMYQyojq5gEKPypCLO4DAKqyyu9R3TQ9AAAAAElFTkSuQmCC',
			'CC9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WEMYQxlCGVqRxURaWRsdHR2mOiCJBTSKNLg2BAQEIIs1iDSwNgQ6iCC5L2rVtFUrMyOzpiG5D6SOIQSuDiHWEBgagmaHYwOqOohbHFHEIG5mRBEbqPCjIsTiPgC/F8x2tEEDcQAAAABJRU5ErkJggg==',
			'F3E9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNZQ1hDHaY6IIkFNIi0sjYwBASgiDE0ujYwOoigigHVwcXATgqNWhW2NHRVVBiS+yDqGKaKYJjH0IBFDM0ObG7BdPNAhR8VIRb3AQCfh8ylL3kKPQAAAABJRU5ErkJggg==',
			'D22F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUNDkMQCprC2Mjo6OiCrC2gVaXRtCEQTY2h0QIiBnRS1dNXSVSszQ7OQ3AdUN4WhlRFdbwDDFHQxID8ATWwKawOjA6pYaIBoqGsoqlsGKvyoCLG4DwCIfcqar8p0/AAAAABJRU5ErkJggg==',
			'890B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYQximMIY6IImJTGFtZQhldAhAEgtoFWl0dHR0EEFRJ9Lo2hAIUwd20tKopUtTV0WGZiG5T2QKYyCSOqh5DGC9IihiLFjswHQLNjcPVPhREWJxHwCtBsvIthqKfQAAAABJRU5ErkJggg==',
			'31F0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA1qRxQKmMAawNjBMdUBW2coKEgsIQBabAtTbwOggguS+lVGropaGrsyahuw+VHVQ83CJodoRANaL6hZRoItZQaoHQfhREWJxHwAay8jjdJNIowAAAABJRU5ErkJggg==',
			'E126' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDAGMDo6BASgiLEGsDYEOgigiAH1AsWQ3RcatSpq1crM1Cwk94HVtTKimQcUm8LoIIIuFoApxujAgKI3NIQ1lDU0AMXNAxV+VIRY3AcAgSXKAjktZT0AAAAASUVORK5CYII=',
			'2205' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2Quw3AIAwFHwUbkH1MQe9IcRGmgYINSDagYcpQOp8ykeLXnfw5Gf1WCX/KJ36WzYJqhBVz1RaIId3HxWXv/YmhIIc0B9J+e2+trzFqP0a1iZNTs2M7X5kd1IwbmrlBIWDtJzIJVWz0g/+9mAe/AxRSyp9oDqiwAAAAAElFTkSuQmCC',
			'0F80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGVqRxVgDRBoYHR2mOiCJiUwRaWBtCAgIQBILaAWpc3QQQXJf1NKpYatCV2ZNQ3Ifmjq4GGtDIIoYNjuwuYURpAvNzQMVflSEWNwHAAHYy0aNWQOPAAAAAElFTkSuQmCC',
			'41B2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjAEsIYyTHVAFgthDGBtdAgIQBJjDGENYG0IdBBBEmMF6W10aBBBct+0aauiloauWhWF5L4AiLpGZDtCQ4FiDQGtGG4BqUYXA7oFVYw1lDWUMTRkMIQf9SAW9wEAJWjKoPqDNjwAAAAASUVORK5CYII=',
			'11B3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUIdkMRYHRgDWBsdHQKQxEQdWANYGwIaRND1Njo0BCC5b2XWqqiloauWZiG5D00dQgybeVjtQHNLCGsoupsHKvyoCLG4DwBoU8hx2C438gAAAABJRU5ErkJggg==',
			'EDA7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQximMIaGIIkFNIi0MoQyNIigijU6OjpgiLkCyQAk94VGTVuZuipqZRaS+6DqWhnQ9YYGTMEQawgIQBNrZW0IdEB3M7rYQIUfFSEW9wEAUQ7Or5B0fhwAAAAASUVORK5CYII=',
			'10B1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGVqRxVgdGENYGx2mIouJOrC2sjYEhKLqFWl0bXSA6QU7aWXWtJWpoauWIrsPTR1CrCEATQxsB5oY2C0oYqIhYDeHBgyC8KMixOI+AOTkya6XylUZAAAAAElFTkSuQmCC',
			'C9F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDA6Y6IImJtLK2sjYwBAQgiQU0ijS6NjA6iCCLNaCIgZ0UtWrp0tTQVVFhSO4LaGAMdG1gmIqqlwGoF2gXih0sIDEUO7C5BexmoHnIbh6o8KMixOI+ABh+zBp+FnMvAAAAAElFTkSuQmCC',
			'1D13' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB1EQximMIQ6IImxOoi0MoQwOgQgiYk6iDQ6hjA0iKDoFWl0mMLQEIDkvpVZ04Bo1dIsJPehqUMRw2YemlgrwxQ0t4SIhjCGOqC4eaDCj4oQi/sAy3jKa3Td3bcAAAAASUVORK5CYII=',
			'ED69' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGaY6IIkFNIi0Mjo6BASgijW6Njg6iGCIMcLEwE4KjZq2MnXqqqgwJPeB1Tk6TMXUCyQxxdDtwHALNjcPVPhREWJxHwBiys4Jc2LlbQAAAABJRU5ErkJggg==',
			'B697' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUNDkMQCprC2Mjo6NIggi7WKNLI2BKCKTRFpAIkFILkvNGpa2MrMqJVZSO4LmCLayhAS0MqAZp4DUAZdzLEhIIABwy2ODljcjCI2UOFHRYjFfQCDU80TbjgGpgAAAABJRU5ErkJggg==',
			'10D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGVqRxVgdGENYGx2mOiCJiTqwtrI2BAQEoOgVaXRtCHQQQXLfyqxpK1NXRWZNQ3Ifmjo8YtjswOKWEEw3D1T4URFicR8AE8bJxxay2yYAAAAASUVORK5CYII=',
			'428B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37pjCGMIQyhjogi4WwtjI6OjoEIIkxhog0ujYEOoggibFOYWh0RKgDO2natFVLV4WuDM1Ccl/AFKAtaOaFhjIEsKKZB1TlgCnG2oCul2GKaKgDupsHKvyoB7G4DwA8r8q26SFYIwAAAABJRU5ErkJggg==',
			'4C68' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37pjCGMoQyTHVAFgthbXR0dAgIQBJjDBFpcG1wdBBBEmOdItLA2sAAUwd20rRp01YtnbpqahaS+wJA6tDMCw0F6Q1EMY9hCsgOdDFMt2B180CFH/UgFvcBAJzBzKfg/Xa7AAAAAElFTkSuQmCC',
			'BC06' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMEx1QBILmMLa6BDKEBCALNYq0uDo6OgggKJOpIG1IdAB2X2hUdNWLV0VmZqF5D6oOgzzQHpFsNghQsAt2Nw8UOFHRYjFfQAePc3C0itHAwAAAABJRU5ErkJggg==',
			'2C0E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMIYGIImJTGFtdAhldEBWF9Aq0uDo6IgixgAUY20IhIlB3DRt2qqlqyJDs5DdF4CiDgwZHTDFWBsw7QCqwnBLaCimmwcq/KgIsbgPAFIkyeFucRdzAAAAAElFTkSuQmCC',
			'935E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANYQ1hDHUMDkMREpoi0sjYwOiCrC2hlaHTFFGtlnQoXAztp2tRVYUszM0OzkNzH6srQytAQiKIXKNLogCYmALYDVQzkFkZHRxQxkJsZQhlR3DxQ4UdFiMV9AOoayYLl41nfAAAAAElFTkSuQmCC',
			'FAEF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDHUNDkMQCGhhDWBsYHRhQxFhbMcVEGl0RYmAnhUZNW5kaujI0C8l9aOqgYqKhmGLY1OEQC3VEERuo8KMixOI+AIc5yxApLdILAAAAAElFTkSuQmCC',
			'DA98' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGaY6IIkFTGEMYXR0CAhAFmtlbWVtCHQQQRETaXRtCICpAzspaum0lZmZUVOzkNwHUucQEoBmnmioAxbzHNHFpgDF0NwSGgA0D83NAxV+VIRY3AcA/pnOt48U+pIAAAAASUVORK5CYII=',
			'774C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNFQx0aHaYGIIu2MjQ6tDoEiKCLTXV0YEEWmwIUDXR0QHFf1KppKzMzs5Ddx+jAEMDaCFcHhqxAUdbQQBQxEaAoQyOqHQFAUaDNKG6BiqG6eYDCj4oQi/sAvr3LzUg6Y5YAAAAASUVORK5CYII=',
			'C7C4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WENEQx1CHRoCkMREWhkaHR0CGpHFAhoZGl0bBFpRxBoYWlkbGKYEILkvatWqaUuBVBSS+4DyAawNjA6oehkdgGKhISh2sDawNgiguUUErBNZjDVEpIEBzc0DFX5UhFjcBwDS3M4rXOlsFAAAAABJRU5ErkJggg==',
			'9779' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM3QMQ6AIAxA0Tp0d6j3wYG9Djh4mjpwA+AGLpxSJDEp0VGj7fYSwk8hX0bgT/tKH/LgrONolFGA1QgzK2N/2GSotaLjaTUpxZzylpdZ9aEFhgBRvwXfmaKirfcoRZs/KJCgQNOCXK1p/up+D+5N3w4Zh8u1qpmzWQAAAABJRU5ErkJggg==',
			'70FF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA0NDkEVbGUNYGxgdUFS2srZiiE0RaXRFiEHcFDVtZWroytAsJPcxOqCoA0PWBkwxkQZMOwIaMN0S0AB0M7pbBij8qAixuA8Ab9XIU1ijSZUAAAAASUVORK5CYII=',
			'65E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHUMDkMREpog0sDYwOiCrC2jBItYgEgIUc3VAcl9k1NSlS0NXRkUhuS9kCkOjK8hcZL2t2MREgGKMDshiIlNYW1kbGAKQ3ccawBjCGuow1WEQhB8VIRb3AQDsl8tmu6j82QAAAABJRU5ErkJggg==',
			'D752' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgNEQ11DHaY6IIkFTGFodG1gCAhAFmsFiTE6iKCKtbJOZWgQQXJf1NJV05ZmZq2KQnIfUF0AkGxEsaOV0QEsgyLG2sAKsh3FLSINjI4OAahuBtoYyhgaMgjCj4oQi/sAnXrN7L8CHpUAAAAASUVORK5CYII=',
			'8246' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHaY6IImJTGFtZWh1CAhAEgtoFQGqcnQQQFEH1Bno6IDsvqVRq5auzMxMzUJyH1DdFNZGRzTzGAJYQwMdRFDEGB0YGh1RxIBuaQDagqKXNUA01AHNzQMVflSEWNwHAANBzNthfHFNAAAAAElFTkSuQmCC',
			'D462' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYWhlCGaY6IIkFTGGYyujoEBCALAZUxdrg6CCCIsboygqkRZDcF7UUCKYCaST3BbSKtLI6OjSi2NEqGuoKMhXVjlZWkO2obmkFuQXTzYyhIYMg/KgIsbgPAPCKzZgfk5whAAAAAElFTkSuQmCC',
			'3D83' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7RANEQxhCGUIdkMQCpoi0Mjo6OgQgq2wVaXRtCGgQQRabItIIVNYQgOS+lVHTVmaFrlqahew+VHW4zcMihs0t2Nw8UOFHRYjFfQCPp81UPGE6qAAAAABJRU5ErkJggg==',
			'E4AC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMEwNQBIDsqcyhDIEiKCKhTI6OjqwoIgxurI2BDoguy80aunSpasis5DdF9Ag0oqkDiomGuoaii7GAFbHgiEWgOIWkJuBYihuHqjwoyLE4j4AbZrMppeT1AkAAAAASUVORK5CYII=',
			'7F28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGaY6IIu2ijQwOjoEBKCJsTYEOoggi00B8QJg6iBuipoatmpl1tQsJPcxgnS1MqCYx9oAFJvCiGKeCEgsAFUsACjG6ICqFyTGGhqA6uYBCj8qQizuAwBNfst7MTaRJQAAAABJRU5ErkJggg==',
			'44AF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpI37pjC0MkxhDA1BFgthmMoQyuiArI4xBCji6IgixjqF0ZW1IRAmBnbStGlLly5dFRmaheS+gCkirUjqwDA0VDTUNRRVDOQWdHVEiw1U+FEPYnEfANGtyb92V8xgAAAAAElFTkSuQmCC',
			'E222' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGaY6IIkFNLC2Mjo6BASgiIk0ujYEOoigiDE0OoBkkNwXGrVq6aqVWauikNwHVDeFoRWkFkVvAFgURYzRASyK6haIKIqbRUNdQwNDQwZB+FERYnEfADpgzNcypPoAAAAAAElFTkSuQmCC'        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && PHPFMG_USER == $_POST['Username'] &&
            defined( 'PHPFMG_PW' )   && PHPFMG_PW   == $_POST['Password']
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created aumotically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>