var socitm_language_opt;


var sArg = '?code='+ socitm_custcode; 


if (socitm_language_opt==undefined) 
{
    socitm_language_opt='';
} 
else
{
    sArg += '&lang_code='+socitm_language_opt 
}
 
document.write('<script type="text/javascript" src="/site/javascript/socitm_funct.js'+sArg+'"><\/script>');


document.write('<script type="text/javascript" src="http://socitm.govmetric.com/hitcounter.aspx?code='+ socitm_custcode +'"><\/script>');