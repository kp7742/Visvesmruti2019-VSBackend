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

    $(".toggle-password").click(function() {
        $(this).toggleClass("zmdi-eye zmdi-eye-off");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });

    var compevents = ["Select Computer Dept's event", "Paper Presentation", "Poster Presentation", "Programming Date", "Placement Drive", "Pitchers", "Pragmatist of Wall Street", "Puzzle with Snake and Ladder"]
    var compeventcode = ["", "COMP-PAPER", "COMP-POST", "COMP-PROG", "COMP-PLAC", "COMP-PITCH", "COMP-PRAGM", "COMP-PUZZ"]
    var compteamcount = [ 0,2, 2, 1, 1, 3, 3, 3]
   
    var civilevents = ["Select Civil's Dept event", "Paper Presentation", "Poster Presentation", "Model Presentation", "E-Placement", "Absolute H2O", "Chakravyuh"]
    var civileventcode = ["","CIVIL-PAPER", "CIVIL-POST", "CIVIL-MODEL", "CIVIL-EPLA", "CIVIL-AH2O", "CIVIL-CHKR"]
    var civilteamcount = [ 0,2, 3, 3, 1, 3, 4]
   
    var chemevents = ["Select Chemical's Dept event",  "Paper Presentation", "Poster Presentaion", "Model Presentation", "Chem-O-Quiz", "Chem-O-Live", "Hepta League(Cricket]"]
    var chemeventcode = ["", "CHEM-PAPER", "CHEM-POST", "CHEM-MODEL", "CHEM-OQUIZ", "CHEM-OLIVE", "CHEM-HEPTA"]
    var chemteamcount = [0, 2, 2, 3, 3, 3, 7]
   
    var mechevents = ["Select Mechanical's Dept event", "Paper Presentation", "Poster Presentation", "Model Presentation", "Junk Yard", "Lathe War"]
    var mecheventcode = ["", "MECH-PAPER", "MECH-POST", "MECH-MODEL", "MECH-JUNKY", "MECH-LATH"]
    var mechteamcount = [ 0,2, 2, 4, 4, 2]
   
    var electevents = ["Select Electrical's Dept event", "Paper Presentation", "Poster Presentation", "Model Presentation", "E-Google", "Virtual Placement", "Buzz Wire", "E-Quiz", "Aqua Robo"]
    var electeventcode = ["","ELEC-PAPER", "ELEC-POST", "ELEC-MODEL", "ELEC-EGOG", "ELEC-VIRT", "ELEC-BUZZ", "ELEC-QUIZ", "ELEC-AQUA"]
    var electteamcount = [ 0,3, 3, 4, 1, 1, 1, 3, 5]
   
    var scihumevents = ["Select Science & Humanities's Dept event","Musing Fizik"]
    var scihumeventcode = ["", "SCIH-MUZF"]
    var scihumteamcount = [ 0,6]
   
    var bvocsevents = ["Select BVOC Software's Dept event", "Blind Coding", "Techno Castle", "Social Media Quiz"]
    var bvocseventcode = [ "BVOC-BCODE", "BVOC-TECH", "BVOC-QUIZ"]
    var bvocsteamcount = [0,1, 2, 2]

    var ii;

    var test, test1, test2    
    $('#Department').on('change', function() {
    var eventName = $('#Department').val()
    switch(eventName) {
      case 'comp':
        test1 = compeventcode
        test=compevents
        test2 = compteamcount
        break;
      case 'mech':
        test1 = mecheventcode
       test = mechevents
       test2 = mechteamcount
        break;
      case 'civil':
        test1 = civileventcode
        test = civilevents
        test2 = civilteamcount
        break;
      case 'chem':
        test1 = chemeventcode
        test = chemevents
        test2 = chemteamcount
        break;
      case 'elec':
        test1 = electeventcode
        test = electevents
        test2 = electteamcount
        break;
      case 'sci':
        test1 = scihumeventcode
        test = scihumevents
        test2 = scihumteamcount
        break;
      case 'bvoc':
        test1 = bvocseventcode
        test = bvocsevents
        test2 = bvocsteamcount
    }

    var add = ''
    var Eventlist = $('#Eventlist')
    $('#email').empty()
    Eventlist.empty();
    for (var i = 0; i < test.length; i++) {
      add += `<option value="${test1[i]}">${test[i]}</option>`
    }
    Eventlist.append(add)
  })

  $('#Eventlist').on('change', function() {
    var email = $('#email')
    ii = test2[test1.indexOf($('#Eventlist').val())]
    email.empty()
    var add = ''
    for (var i = 0; i <ii; i++) {
      add += `<div class="form-group">Email ${i+1}<input type="text" placeholder="Enter participant ${i + 1} Email" id="email${i}" class="form-input"></div>`
    }
    email.append(add)
  })


  // Submit here
  async function submitRes() {
      let url = 'https://visvesmruti.tech/api/event/register/'

    var eventCode = $('#Eventlist').val()
    var formData = new FormData();
    formData.append('eventcode', eventCode)
    formData.append('teamlength', ii)
    for (let i = 0; i < ii; i++) {
      formData.append(`email${i}`,$(`#email${i}`).val())
    }
    // formData.append('email0', email0)
    var res = await fetch(url, {
      method: 'POST',
      body: formData,
    })
    var result = await res.json()
    if (result.Code == 200) {
      alert(result.Message)
    } else if (result.Code == 404) {
      alert(`Error: ${result.Message}`)
    }
    $('#signup-form').trigger('reset')    
  }

  $('#submit').click(function() {
    submitRes()
  })

})(jQuery);