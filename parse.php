<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Demo: Ajax and Prototype: Display multiple pages without refreshing</title>
	<script type="text/javascript" src="prototype.js"></script>
	<script type="text/javascript">
	// <![CDATA[
   document.observe('dom:loaded', function () {
		var newsCat = document.getElementsByClassName('newsCat');
		for (var i = 0; i < newsCat.length; i++) {
			$(newsCat[i].id).onclick = function () {
				getCatPage(this.id);
			}
		}
	});
	
	function getCatPage(id) {
		var url = 'load-content.php';
		var rand   = Math.random(9999);
		var pars   = 'id=' + id + '&rand=' + rand;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: showLoad, onComplete: showResponse} );
	}
	
	function showLoad () {
		$('newsContent').style.display = 'none';
		$('newsLoading').style.display = 'block';
	}
	
	function showResponse (originalRequest) {
		var newData = originalRequest.responseText;
		$('newsLoading').style.display = 'none';
		$('newsContent').style.display = 'block';		
		$('newsContent').innerHTML = newData;
	}
	// ]]>
	</script>		
	<style type="text/css" media="screen">
	<!--
	body {
		font: 1em Verdana, Arial, Helvetica, sans-serif;		
		}
	
	#tagLine {		
		color: #d39819;
		margin: 0 0 15px 0;
		font-style: italic;
		font-size: 1.05em;
		font-family: georgia, arial, helvetica;
		}	
			
	#credits {
		font: normal 66% verdana, helvetica, arial; 
		padding: 0.5em 0;
		margin: 2em 0; 
		border-top: 1px dotted #c0c0c0;
		border-bottom: 1px dotted #c0c0c0;
		}	
	
	h1 {
		font: normal 2em georgia, arial, helvetica;
		margin: 0;
		padding: 0;
		color: #D35619;
		}
	h2 {
		font: normal 1.5em georgia, arial, helvetica;
		margin: 0;
		padding: 0;
		color: #CCCC66;
		}
	
	h3 {
		font-weight: normal;
		text-align:center;
		}
			
	
	#newsContainer {
		background-color:white;
		width: 1024px;
		height: 768px;
		margin: 0 auto; /* we center our container div */
		border: 1px solid #99CC00;
		padding: 7px;
		}
	
	#newsCategoriesContainer {
		float: left; 
		height: 300px;
		background-color: #fafafa;
		margin-right:10px;
	}
	
	#newsCategoriesContainer .newsCat{
		margin: 10px;
		padding: 4px;
		text-align:center;
		display: block;
		cursor:pointer;
		border:1px solid #ccc;
		width: 100px;
	}
	
	#newsCategoriesContainer .newsCat:hover{
		background-color:#FFFFCC;
	}
	
	#newsContent {
		padding: 10px;
		width:850px; 
		height:750px;
		background-color:#F2F2F2;
		overflow:auto;
	}
	
	#newsLoading {
		margin-top: 10px;
		text-align:center;
		display:none;
	}
	-->
	</style>
<script type="text/javascript">
      <!--
      i = 0;
      tempo = 50;
      tamanho = 826; // tamanho da barra de rolagem  >> Ver arquivo Leiame.txt

      function Rolar() {
        document.getElementById('newsContent').scrollTop = i;
        i++;
        t = setTimeout("Rolar()", tempo);
        if (i == tamanho) {
          i = 0;
        }
      }
      function Parar() {
        clearTimeout(t);
      }
      //-->
    </script>
</head>
<body>
		<h1><center>AWS CSV PARSE</center></h1>
<br />

	<div id="newsContainer" style="overflow: hidden;cursor: default;">
		<div id="newsCategoriesContainer" style="cursor: default; overflow: hidden;">
			<div class="newsCat" id="cat1">AWS CSV</div>
			<div class="newsCat" id="cat2">CONTROLE</div>
			<div class="newsCat" id="cat3">MERGED</div>
		</div>
		<div id="newsLoading">Loading <img src="loading_indicator.gif" title="Loading..." alt="Loading..." border="0" /></div>
		<div id="newsContent"></div>
	</div><br><center><a href="index.php">VOLTAR</a></center>

</body>
</html>
