! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_cat_fichier = $('#table_gestion_doc_webs').dataTable({
  	"bStateSave": true,
    "ajax": "module/doc/table/php/data_doc.php?job=get_doc",
    "columns": [
	  { "data": "id","sClass": "" },
	  { "data": "nom","sClass": "" },
	  { "data": "type","sClass": "" },
	  { "data": "taille","sClass": "" },
	  { "data": "actif","sClass": "" },
	  { "data": "ajout","sClass": "" },
	  { "data": "modif","sClass": "" },
	  { "data": "down","sClass": "" },
      { "data": "functions","sClass": "" }
    ],
    dom: 'Bfrtip',
	"buttons": [
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':visible'
			}
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':visible'
			}
		},{
			extend: 'print',
			exportOptions: {
				columns: ':visible'
			}
		}, 'colvis'
	],
		
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
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
  var form_company = $('#form_cat_fichier');
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
  function hide_lightbox_del(){
    $('#modal-confirm').hide();
  }
  $(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });
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
  
	$(document).on('click', '#refresh', function(e){
		table_cat_fichier.api().ajax.reload(function(){
		hide_loading_message();
		show_message("Rafraîchissement terminé", 'success');
		}, true);
	});
	
	$(document).on(setInterval(function(){
		table_cat_fichier.api().ajax.reload(function(){
		hide_loading_message();
		}, true);
	}, 20000)
	);
	
  
  $(document).on('click', '#function_edit', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/doc/table/php/data_doc.php?job=get_doc_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Modification du nom');
        $('#form_cat_fichier button').text('Enregistrement');
        $('#form_cat_fichier').attr('class', 'form edit');
        $('#form_cat_fichier').attr('data-id', id);
        $('#form_cat_fichier .field_container label.error').hide();
        $('#form_cat_fichier .field_container').removeClass('valid').removeClass('error');
        $('#form_cat_fichier #nom').val(output.data[0].nom);
        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message("Une erreur s'est produite lors de l'enregistrement " + textStatus, 'error');
    });
  });
  
  $(document).on('submit', '#form_cat_fichier.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_cat_fichier').attr('data-id');
      var form_data = $('#form_cat_fichier').serialize();
      var request   = $.ajax({
        url:          'module/doc/table/php/data_doc.php?job=edit_doc&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_cat_fichier.api().ajax.reload(function(){
            hide_loading_message();
            var cat_name = $('#nom').val();
            show_message("Dossier '" + cat_name + "' modifié avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
      });
    }
  });
 
	
    
		
  $(document).on('click', 'a#del', function(e){
		e.preventDefault();
		var id      = $(this).data('id');
		var doc_up      = $(this).data('doc');
		var cat_name = $(this).data('name');
	
		t.confirm({
			title: cat_name,
			content: "Confirmation de supprission de l'entrés " + cat_name,
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-danger",
					text: "Confirmer",
					action: function() {
						t.alert("Supprission terminée")
						show_loading_message();
						
						var request = $.ajax({
						url:          'module/doc/table/php/data_doc.php?job=delete_doc&id=' + id + '&cat=' + doc_up,
						cache:        false,
						dataType:     'json',
						contentType:  'application/json; charset=utf-8',
						type:         'get'
						});
						request.done(function(output){
						if (output.result == 'success'){
						  table_cat_fichier.api().ajax.reload(function(){
							hide_loading_message();
							hide_lightbox_del();
							show_message("Catégorie '" + cat_name + "' effacée avec succès.", 'success');
						  }, true);
						} else {
						  hide_loading_message();
						  show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
						}
						});
						request.fail(function(jqXHR, textStatus){
						hide_loading_message();
						show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
						});
					}
				},
				cancelAction: {
					text: "Annuler",
					action: function() {
						t.alert("La supprission est annulée")
					}
				}
			}
			
		})
		
      
  });

});
}(jQuery);