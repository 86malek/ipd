$(document).ready(function(){
var id_cat_mere = $('#table_traitement').attr('data-idcatt');
  var id_import = $('#table_traitement').attr('data-id');
  var name_user = $('#table_traitement').attr('data-name');
  var id_user = $('#table_traitement').attr('data-ide');
  
  var table_companies = $('#table_traitement').dataTable({
	 "bStateSave": true,
	  "ajax": "module/client/table/php/data_client_traitement.php?job=get_traitement_contact&id_import=" + id_import+"&id_cat_mere=" + id_cat_mere,
	  "deferRender": true,
    "columns": [
	
		{ "data": "alerte",      "sClass": "" },
		{ "data": "rs", "sClass": "" },
		
      { "data": "civ", "sClass": "" },
	  { "data": "prenom", "sClass": "" },
	  { "data": "nom", "sClass": "" },
	  { "data": "fonctione", "sClass": "" },
	  { "data": "email", "sClass": "" },
	  { "data": "fonction", "sClass": "" }
	  
      
    ],
    dom: 'Bfrtip',
	"buttons": [
            'colvis'
        ],
    "order": [[ 1, "desc" ]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Contacts par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Contacts _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Contacts)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  });
  jQuery.validator.setDefaults({
    success: 'valid',
    rules: {
      fiscal_year: {
        required: true,
        min:      2000,
        max:      2025
      }
    },
    errorPlacement: function(error, element){
      error.insertBefore(element);
    },
    highlight: function(element){
      $(element).parent('.field_container').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container').addClass('valid').removeClass('error');
    }
  });
  var form_company = $('#form_company');
  form_company.validate();

  function show_message(message_text, message_type){
    $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
    $('#message_container').show();
    if (typeof timeout_message !== 'undefined'){
      window.clearTimeout(timeout_message);
    }
    timeout_message = setTimeout(function(){
      hide_message();
    }, 8000);
  }
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }

  function show_loading_message(){
    $('#loading_container').show();
  }
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }
  /*$(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });*/
  $(document).on('click', '.lightbox_close', function(){
    hide_lightbox();
  });
  $(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });
  
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

  

  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/client/table/php/data_client_traitement.php?job=get_traitement_add_contact',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
	
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
    var month = '0'+month;
    }
    if(day.toString().length == 1) {
    var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
    var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
    var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
    var second = '0'+second;
    }   
    var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
	
    request.done(function(output){

      if (output.result == 'success'){

		  if(output.data[0].reporting == 1){
			  
        $('.lightbox_content h2').text('TRAITEMENT fiche Client');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();		
        $('#form_company .field_container').removeClass('valid').removeClass('error');
		
    		$('#form_company #debut').val(dateTime);	
			
			
			
			
			$('#form_company #tel2').val(output.data[0].tel2);
			$("#form_company #service option").filter(function() {
          return $(this).val() == output.data[0].service; 
        }).prop('selected', true);
				
			
			$("#form_company #civ option").filter(function() {
          return $(this).val() == output.data[0].civ; 
        }).prop('selected', true);
		
    		$('#form_company #prenom').val(output.data[0].prenom);
    		$('#form_company #nom').val(output.data[0].nom);
    		/*$('#form_company #fc').val(output.data[0].fc);*/
        $("#form_company #fc option").filter(function() {
          return $(this).val() == output.data[0].fc; 
        }).prop('selected', true);
    		$('#form_company #email').val(output.data[0].email);
    		$('#form_company #lk').val(output.data[0].lk);
			
			$("#form_company #stat option").filter(function() {
          return $(this).val() == output.data[0].stat; 
        }).prop('selected', true);
		
        $('#form_company #commentaire_collab').val(output.data[0].commentaire_collab);
		
        $("#form_company #type option").filter(function() {
          return $(this).val() == output.data[0].type; 
        }).prop('selected', true);
		
		  }else{
			  
		    $('.lightbox_content h2').text('TRAITEMENT fiche Client');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');	
		
		
		
		/*$('#form_company #rs').val(output.data[0].rs);
		$('#form_company #nsiret').val(output.data[0].siret);
			$('#form_company #ad').val(output.data[0].ad);
			$('#form_company #cp').val(output.data[0].cp);
			$('#form_company #ville').val(output.data[0].ville);
			$('#form_company #tel').val(output.data[0].tel);
			*/
			
			$('#form_company #tel2').val(output.data[0].tel2);
				$("#form_company #service option").filter(function() {
          return $(this).val() == output.data[0].service; 
        }).prop('selected', true);
		
			$("#form_company #civ option").filter(function() {
          return $(this).val() == output.data[0].civ_o; 
        }).prop('selected', true);
		
    		$('#form_company #prenom').val(output.data[0].prenom_o);
    		$('#form_company #nom').val(output.data[0].nom_o);
    		$('#form_company #fc').val(output.data[0].fc_o);
    		$('#form_company #email').val(output.data[0].email_o);
			
        $('#form_company #commentaire_collab').val(output.data[0].commentaire_collab);
		
    		$('#form_company #lk').val(output.data[0].lk);
			
			$("#form_company #stat option").filter(function() {
          return $(this).val() == output.data[0].stat; 
        }).prop('selected', true);
		
    		$('#form_company #debut').val(dateTime); 
			
        $("#form_company #type option").filter(function() {
          return $(this).val() == output.data[0].type; 
        }).prop('selected', true);
		  }
		
		$('#form_company #commentaire').val(output.data[0].commentaire);

        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
    });
  });
  
  $(document).on('submit', '#form_company.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      /*show_loading_message();*/
	  	var now     = new Date(); 
		var year    = now.getFullYear();
		var month   = now.getMonth()+1; 
		var day     = now.getDate();
		var hour    = now.getHours();
		var minute  = now.getMinutes();
		var second  = now.getSeconds(); 
		if(month.toString().length == 1) {
		var month = '0'+month;
		}
		if(day.toString().length == 1) {
		var day = '0'+day;
		}   
		if(hour.toString().length == 1) {
		var hour = '0'+hour;
		}
		if(minute.toString().length == 1) {
		var minute = '0'+minute;
		}
		if(second.toString().length == 1) {
		var second = '0'+second;
		}   
		var dateTime_fin = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;

      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/client/table/php/data_client_traitement.php?job=edit_traitement_contact&id=' + id + '&fin='+ dateTime_fin,
        cache:        true,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
			window.location.reload(function(){
				table_companies.api().ajax.reload;
				/*hide_loading_message();*/
				/*var company_name = $('#raison').val();
				show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');*/			
			}, true); 
			
			/*table_companies.api().ajax.reload(function(){				
				hide_loading_message();
				var company_name = $('#raison').val();
				show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');			
			}, true); */        
        } else {
          hide_loading_message();
          show_message('Edit request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Edit request failed: ' + textStatus, 'error');
      });
    }
  });







  $(document).on('click', '#add_contact', function(e){
    e.preventDefault();   
      $('.lightbox_content h2').text('TRAITEMENT fiche Client : Ajout');
      $('#form_company button').text('ENREGISTREMENT');
      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');
      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');
	  
      var now     = new Date(); 
      var year    = now.getFullYear();
      var month   = now.getMonth()+1; 
      var day     = now.getDate();
      var hour    = now.getHours();
      var minute  = now.getMinutes();
      var second  = now.getSeconds(); 
      if(month.toString().length == 1) {
      var month = '0'+month;
      }
      if(day.toString().length == 1) {
      var day = '0'+day;
      }   
      if(hour.toString().length == 1) {
      var hour = '0'+hour;
      }
      if(minute.toString().length == 1) {
      var minute = '0'+minute;
      }
      if(second.toString().length == 1) {
      var second = '0'+second;
      }   
      var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;


        $('#form_company #civ').val('');
        $('#form_company #prenom').val('');
        $('#form_company #nom').val('');
        $('#form_company #fc').val('');
        $('#form_company #email').val('');
        $('#form_company #commentaire_collab').val('');
        $('#form_company #lk').val('');
        $('#form_company #stat').val('');
        $('#form_company #type').val(0);
        $('#form_company #debut').val(dateTime);

    show_lightbox();
  });

  $(document).on('submit', '#form_company.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();   
      var now     = new Date(); 
      var year    = now.getFullYear();
      var month   = now.getMonth()+1; 
      var day     = now.getDate();
      var hour    = now.getHours();
      var minute  = now.getMinutes();
      var second  = now.getSeconds(); 
      if(month.toString().length == 1) {
      var month = '0'+month;
      }
      if(day.toString().length == 1) {
      var day = '0'+day;
      }   
      if(hour.toString().length == 1) {
      var hour = '0'+hour;
      }
      if(minute.toString().length == 1) {
      var minute = '0'+minute;
      }
      if(second.toString().length == 1) {
      var second = '0'+second;
      }   
      var dateTime_fin = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;   
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/client/table/php/data_client_traitement.php?job=add_traitement_contact&fin='+ dateTime_fin,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
      window.location.reload(function(){
        table_companies.api().ajax.reload;
        /*hide_loading_message();*/
        /*var company_name = $('#raison').val();
        show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');*/      
      }, true); 
      
      /*table_companies.api().ajax.reload(function(){       
        hide_loading_message();
        var company_name = $('#raison').val();
        show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');      
      }, true); */        
        } else {
          hide_loading_message();
          show_message('Edit request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
      });
    }
  });




});