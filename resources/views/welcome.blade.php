
        <div class="row col-xs-12 col-sm-12 col-lg-12 col-md-12">
            <form id="frmTiposEquipos" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <fieldset class="agrupar col-xs-12">
                        <legend class="agrupar">Datos Generales:</legend>
                        <div class="form-row col-lg-3 col-sm-3 ml-auto" style="display: none;">
                            <label>IdTipo:</label>
                            <input type="text" name="IdTipo" id="IdTipo">
                        </div>
                        <div class="form-row col-lg-4 col-sm-4">
                            <label>Catálogo:</label>
                            <input type="text" name="Tipo" id="Tipo" class="form-control">                            
                        </div>

                        <div class="form-row col-lg-4 col-sm-4">
                            <label>Estado:</label>
                            <select name="Estado" id="Estado" class="form-control">
                                <option value="A">Activo</option>
                                <option value="I">Inactivo</option>
                            </select>                     
                        </div>         
                    </fieldset>                           
                    <br><br><br>
                </div>
            </form>
        </div>

        <script>
            const modoLectura = "{{$ModoLectura}}";
             if(modoLectura === "S"){
              $("#btnAgregar").attr("disabled",true);
              $("#btnAgregar").css("display","none");
              $("#btnGuardar").attr("disabled",true);
              $("#btnGuardar").css("display","none");
              $("#formulario").attr("hidden",true);   
              
              $("#Tipo").attr("disabled",true);
              $("#Estado").attr("disabled",true);
            }
            var dataTableTiposEquipos = null;
            $(document).ready(function(){
              $("#IdTipo").val('0');
              $("#ListadoPrincipal").attr('hidden',false);
              $("#formulario").attr('hidden',true);
      
              dataTableTiposEquipos = $("#tblTiposEquipos").DataTable({
                        ajax: {
                           'url': '{{route("tiposequipos.consultar")}}'
                     },
                     dom : 'Bfrtip',
                     buttons: [],
                     columns:[
                       { name:'IdTipo',data:'IdTipo'},
                       { name:'Tipo',data:'Tipo'},                
                       { name:'Estado',data:'Estado'},                
                       {
                           "data":"Estado",
                           "render" : function (data, type, row, meta){
                            
                           if(data === "A"){
                                 return 'Activo';
                                  
                             }
                             else{
                                return 'Inactivo';
                             }
                               
                           
                           }
                      },
                      {'defaultContent':"<button class='btn btn-primary' style='color:white;' id='btnEditarTipoEquipo' > <i class='{{$ModoLectura=='S'?'fa fa-eye':'fa fa-edit'}}' > </i> </button>" }                 
                     ], 
                     columnDefs:[
                        {'targets':[0],'visible':false,'searchable':false} ,              
                        {'targets':[2],'visible':false,'searchable':false}                           
                     ],             
                     iDisplayLength:15
              });
              
      
       
            $("#btnAgregar").on("click",function(){
               $("#ListadoPrincipal").attr('hidden',true);
               $("#formulario").attr('hidden',false);
               $("#Tipo").attr("disabled",false);
               $("#Tipo").val('');
            })
      
            $("#btnCancelar").on("click",function(){
              $("#ListadoPrincipal").attr('hidden',false);
              $("#formulario").attr('hidden',true);
              $("#Tipo").atrr("disabled",false);
              limpiar();
            })
      
          
            $("#btnGuardar").on("click",function(){
                  
                  if($("#Tipo").val().trim() === ""){
                      Swal.fire({
                          position: 'top-center',
                          icon: 'warning',
                          title: 'Notificación',
                          text: 'Debe ingresar el nombre del Catálogo',
                          showConfirmButton: true,
                          timer: 15000
                      })
                  }
                  else{
                      $("#Tipo").attr("disabled",false);
                      let ruta = '{{route("tiposequipos.agregar")}}';
                      let type = 'POST';
                      if($("#IdTipo").val().trim() !== "0"){
                          ruta = '{{route("tiposequipos.actualizar")}}';
                          type = 'PUT';
                      }
      
                      $.ajax({
                          type: type, 
                          url: ruta,
                          data: $('#frmTiposEquipos').serialize(),
                          success: function (data) { 
                               
                               if(data.data[0].CodigoError==="0000"){
                                   Swal.fire({
                                    position: 'top-center',
                                    icon: 'success',
                                    title: 'Notificación',
                                    text: data.data[0].MensajeError,
                                    showConfirmButton: false,
                                    timer: 15000
                                  });		
      
                                  limpiar();
                                  dataTableTiposEquipos.ajax.reload(); 
                                  $("#btnCancelar").click();
                               }
                               else
                               {
                                   Swal.fire({
                                    position: 'top-center',
                                    icon: 'warning',
                                    title: 'Notificación',
                                    text: data.data[0].MensajeError,
                                    showConfirmButton: false,
                                    timer: 15000
                                  });	
                               }
      
                              
                          },
                          error: function(data){
                              console.log(data);
                               Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'Notificación',
                                text: 'Error Inesperado!!',
                                showConfirmButton: false,
                                timer: 15000
                              });				    	 
                          } 
                       });
                  }  
               });  
      
      
           //editar el registro
      
          $("#tblTiposEquipos tbody").on("click","#btnEditarTipoEquipo",function(){
                      var data = dataTableTiposEquipos.row($(this).parents("tr")).data();
                  
                      $("#Tipo").val(data.Tipo);
                      $("#IdTipo").val(data.IdTipo);
                      $("#Estado").val(data.Estado);         
                      $("#ListadoPrincipal").attr('hidden',true);
                      $("#formulario").attr('hidden',false);    
                      $("#Tipo").attr("disabled",true);
                      
              });
      
         //elimina el registro
        
          $("#tblTiposEquipos tbody").on("click","#btnEliminarTipoEquipo",function(){
                      var data = dataTableTiposEquipos.row($(this).parents("tr")).data();
                      
                      $("#Tipo").val(data.Tipo);
                      $("#IdTipo").val(data.IdTipo);
                      $("#Estado").val(data.Estado);            
                      
                      Swal.fire({
                        title: 'Esta seguro(a) de eliminar?',
                        text: "El Tipo de Equipo ya no estará disponible",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar'
                      }).then((result) => {
                        if (result.value) {
                          $.ajax({
                          type: 'PUT', 
                          url: '{{route("tiposequipos.eliminar")}}',
                          data: $('#frmTiposEquipos').serialize(),
                          success: function (data) { 				    	 
                                  if(data.data[0].CodigoError==="0000"){
                                      Swal.fire({
                                      position: 'top-center',
                                      icon: 'success',
                                      title: 'Notificación',
                                      text: data.data[0].MensajeError,
                                      showConfirmButton: false,
                                      timer: 15000
                                      });		
      
                                      limpiar();
                                      dataTableTiposEquipos.ajax.reload(); 
                                      $("#btnCancelar").click();
                                  }
                                  else
                                  {
                                      Swal.fire({
                                      position: 'top-center',
                                      icon: 'warning',
                                      title: 'Notificación',
                                      text: data.data[0].MensajeError,
                                      showConfirmButton: false,
                                      timer: 15000
                                      });	
                                  }                           
                                  },
                                  error: function(data){
                                          console.log(data);
                                          Swal.fire({
                                          position: 'top-center',
                                          icon: 'error',
                                          title: 'Notificación',
                                          text: 'Error Inesperado!!',
                                          showConfirmButton: false,
                                          timer: 15000
                                          });				    	 
                                  } 
                          });                     
      
                         }
                      });
                    });
          }); 
      
          function limpiar(){
              $("#Tipo").val('');
              $("#IdTipo").val('0');
              $("#Estado").val('A');
          }
            
        </script>