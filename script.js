//funkcija za listanje strani
var page;
page = 0;

function pagination(site){
	page = page + site;
	$.ajax({url: "tabelaNarocil.php?page="+page, success: function(result){
      $("#table").html(result);
    }});
	if (page != 0){
	$("#nazaj").show();
	}else{
	$("#nazaj").hide();
	}	
}
//funkcija, ki izpize obvestilo
bootstrap_alert = function() {}
bootstrap_alert.warning = function(message, alerttype) {
  $('#obvestilo').html('<div style="margin-top:20px; margin-bottom:0px" class="alert ' + alerttype + '"><a class="close" data-dismiss="alert"><i class="fa fa-times" aria-hidden="true"></i></a></a><span>' +message +'</span></div>')
	}


//funkcija za posodabljanje narocila
function changeorderstatus(id, orderstatus, element){
	
	//ob kliku na gumb ga spremeni v loading gumbek
	$(element).addClass('disabled');
	$(element).children('i').addClass('fa fa-circle-o-notch fa-spin');

	//spremeni status narocila glede na kliknen gumbek
	var request = $.ajax({
	url: 'changeOrderStatus.php?id=' + id + '&status=' + orderstatus,
	type: 'get',
	dataType: 'html'
  });

	//ce se uspesno izvrsi, se prikaze obvestilo o tem kaj se je naredilo, stran se poskrolla do vrha kjer je obvestilo
	//tabela se ozvezi z novimi podatki in vrstica z narocilom se oznaci po refreshue
	request.done( function ( data ) {
		var statusnarocila;
		var alerttype;
		if (orderstatus == "cancelled"){
			statusnarocila = "<strong>preklicano</strong>.";
			alerttype = "alert-danger";
		}else if  (orderstatus == "completed"){
			statusnarocila = "<strong>zaključeno</strong>.";
			alerttype = "alert-success";
	  }else if  (orderstatus == "processing"){
  	  statusnarocila = "označeno kot <strong>plačano</strong>.";
  	  alerttype = "alert-info";
		}
		bootstrap_alert.warning('Naročilo ' + id + ' je bilo '+ statusnarocila, alerttype);
		$("html, body").animate({ scrollTop: 0 }, "fast");
  	$(element).hide();
	  $.ajax({url: "tabelaNarocil.php?page="+page, success: function(result){
			$("#table").html(result);	
			$(document).ready(function () {
				if  (orderstatus == "processing"){
					$("#" + id).toggleClass("table-active");
					$("#expand"+id).show();
				}else	if  (orderstatus == "completed"){
					$("#" + id).animate({opacity: 0.250,}, 2000, function() {}).animate({opacity: 1,}, 500, function() {});

				}
			
			});
		}});
	});

	//ce pride do tezave se ta izpise v konzoli
	request.fail( function ( jqXHR, textStatus) {
	console.log( 'Sorry: ' + textStatus );
  });
}

//funkcija ki ob kliku na narocilo prikaze vrstico s podrobnostmi o narocilu in vrstico oznaci kot aktivno
//ob kliku na drugo vrstico, prejsnjo skrije in razkrije novo
function show_hide_row(row){
	if ( $("#" + row).hasClass('table-active') ) {
		$("#expand"+row).hide();
		$("#" + row).removeClass("table-active");

	}else{
		$("tr").removeClass("table-active"); 
		$(".hidden_row").hide();
		$("#expand"+row).show();
		$("#" + row).toggleClass("table-active");
	}
} 
$(document).ready(function () {
	$("#menu-toggle").click(function() {
		$("#wrapper").toggleClass("toggled");
	});
});

//funkcija za iskanje po skladiscu ki skrije elemente ki ne ustrezajo iskanemu nizu
function searchInventory() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("table");
  tr = table.getElementsByTagName("tr");

	//preveri vse vrstice ce vsebujejo iskan niz in skrije tiste ki ne
	for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}

//funkcija ob kliku na gumb poslje seznam narocil ki so obkljukana na csvExport.php ki izpljune css
$(document).ready(function () {
$('#csvizvoz').on('click', function(event) {
  event.preventDefault(); // not sure if this is needed.
	  $('#csvizvoz').children('i').removeClass('fa-envelope').addClass('fa-envelope-open');
		setTimeout(
		  function() 
			  {
				$('#csvizvoz').children('i').removeClass('fa-envelope-open').addClass('fa-envelope');    //do something special
				}, 160);
		
	var narocila = $('input[name="narocilo[]"]:checkbox:checked').map(function(){ 
		return this.value; 
	}).get();	
  
	$.ajax({
    url: 'csvExport.php',
    method: 'POST',
    data: {
      'narocilo[]' : narocila,
    },
		success: function(data) {
			bootstrap_alert.warning('Datoteka za uvoz v eSpremnico je pripravljena za <a href="posta.csv">prenos <i class="fa fa-download fa-lg" aria-hidden="true"></i></a>', 'alert-info');	
		}
  });
});
});

//funkcija vstavljanje št- računa v bazo
function AddInvoice(id){
	var invoiceid  = $('#order' + id).val();

  var request = $.ajax({
  url: 'addInvoice.php?orderid=' + id + '&invoiceid=' + invoiceid,
  type: 'get',
  dataType: 'html'
  });

  request.done( function ( data ) {
    $.ajax({url: "tabelaNarocil.php?page="+page, success: function(result){
      $("#table").html(result);
      $(document).ready(function () {
          $("#" + id).toggleClass("table-active");
          $("#expand"+id).show();
      });
    }});
  });
}
