
function carga()
{
  cargaMainSection();

}

function cargaMainSection()
{
  $('#main-section').empty();//borra lo que haya dentro
  imprimeConsulta("main-section","buscarMaterias.php");//imprime lo que va sustituir 
}

function imprimeConsulta(interfaceParaImprimir,nombreDeArchivoDeComando)
{
	$.post('../php/'+nombreDeArchivoDeComando,function(result)
	{
		cambiarContenido(result,interfaceParaImprimir);
	});
}

function cambiarContenido(contenido,lugar)
{
	document.getElementById(lugar).innerHTML = contenido;
}
function cambiarValor(valor,lugar)
{
	document.getElementById(lugar).value = valor;
}

//requiere que le envien una cadena
function mandarAPagina(paginaDestino)
{
	window.location.href = paginaDestino;
}

function recarga()
{
	location.reload();
}

function confElim(destino,id) 
{
	if (window.confirm("Seguro que desea eliminar el registro seleccionado?")) {
	   	   location.href = destino+'?del=' + id;
	}
}
