(function($) {

    $(document).ready(function() {
      $("#Gender").select2({
        minimumResultsForSearch: Infinity
      });
      $("#Sem").select2({
        minimumResultsForSearch: Infinity
      });
      $("#Department").select2({
        minimumResultsForSearch: Infinity
      });
      $("#Eventlist").select2({
        minimumResultsForSearch: Infinity
      });
    });

  $('#department').on('change', function() {
    let department = $('#department').val()
    let others = $('#others')
    others.empty()
    if (department == 'Others') {
      others.append('<input type="text" placeholder="Please Enter your Department" class="form-input" id="othersInput">')
    }
  })


  async function submitRes() {
    let email = $('#email').val(),
        fname = $('#fname').val(),
        lname = $('#lname').val(),
        college = $('#college').val(),
        department = $('#department').val(),
        semester = $('#semester').val(),
        mobile = $('#mobile').val(),
        gender = $('#gender').val(),
        department1 = ''
    
        if (department === 'Others') {
          department1 = $('#othersInput').val()
        } else {
          department1 = department
        }
                
    var formData = new FormData();
    formData.append('email', email);
    formData.append('fname', fname);
    formData.append('lname', lname);
    formData.append('college', college);
    formData.append('department', department1);
    formData.append('semester', semester);
    formData.append('mobile', mobile);
    formData.append('gender', gender);
    let res = await fetch('https://visvesmruti.tech/api/register/', {
      method: 'POST',
      body: formData,
    })
    let result = await res.json()
    if (result.Message === 'Already Registered') {
      alert('You are already registered for Visvesmruti 2k19')
    } else if (res.status == 404) {
      alert('server error')
    } else {
      alert('Successfully registered for Visvesmruti 2k19')
      $('#signup-form').trigger('reset')
    }
  }

  $('#submit').click(function() {
    submitRes()
  })

})(jQuery);