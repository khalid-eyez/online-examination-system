$(document).ready(function() {
    //$('#dataTables-ex2').DataTable();
     $("#dataTables-ex2").DataTable({
    	dom: 'Bfrtip',
         buttons: ['csv','pdf','excel','print']
    });

   
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

        localStorage.setItem('activeTab', $(e.target).attr('href'));

    });

    var activeTab = localStorage.getItem('activeTab');

    if(activeTab){
  
        $('#tabs a[href="' + activeTab + '"]').tab('show');

    }

   });