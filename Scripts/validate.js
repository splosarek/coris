function validate() {
  var args = validate.arguments;
  var element, argument, test, regexp;
  if (args[0] == 'en') {
    var fieldvar = "Field value";
    var notnull = "cannot be empty";
    var wrongformat = "Wrong data format: ";
    var wrongvalue = "Wrong value: ";
  } else {
    var fieldvar = "Warto¶æ pola";
    var notnull = "nie mo¿e byæ pusta";
    var wrongformat = "B³êdny format pola: ";
    var wrongvalue = "B³êdna warto¶æ pola: ";    
  }
  for (var i = 1; i < (args.length - 1); i += 2) 
  { 
    element = document.getElementById(args[i]);
    argument = args[i+1];

    if (argument.indexOf('l') != -1)  // empty (list)
    {
      if (element.value == 0)
      {
        alert(fieldvar + " \"" + args[i] +"\" "+ notnull);
        element.focus();
        return false;
      }
    }
    if (argument.indexOf('r') != -1)  // empty
    {
      if (element.value == "")
      {
        alert(fieldvar + " \"" + args[i] +"\" "+ notnull);
        element.focus();
        return false;
      }
    }
    if (argument.indexOf('j') != -1) // conajmniej jeden checkbox zaznazcony
    {
        var elementName = args[i];
        var iName=1;
        var elementCheck;
        var anyChecked = false;
        while(elementCheck = document.getElementById(elementName + iName))
        {
            if(elementCheck.checked)
            {
                anyChecked = true;
            }
            iName++;
        }
        if(anyChecked)
        {
            continue;
        }
        alert(fieldvar + " \"" + args[i] +"\" "+ notnull);
        return false;
    }

    if (element.value != "" && argument.indexOf('d') != -1) // data
    {
      if (element.value.match(/^\d{4}-\d\d-\d\d$/i)) {
          var arrDate = element.value.split("-");
          var y = arrDate[0];
          var m = parseInt(arrDate[1], 10);
          var d = parseInt(arrDate[2], 10);
          if (d < 1)
          {
            alert(wrongvalue +" \""+ args[i] +"\"");
            element.focus();
            return false;
          }

          if (m > 12) {
            alert(wrongvalue +" \""+ args[i] +"\"");
            element.focus();
            return false;
          }
          if (m < 1)
          {
            alert(wrongvalue +" \""+ args[i] +"\"");
            element.focus();
            return false;
          }
          var days = new Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
          if (((y % 4 == 0) && (y % 100 != 0)) || (y % 400 == 0)) {
              days[2] = 29;
            } else {
              days[2] = 28;
          }
          if (d > days[m]) {
              alert(wrongvalue +" \""+ args[i] +"\"");
              element.focus();
              return false;
          }
      } else {
          alert(wrongformat +" \""+ args[i] +"\"");
          element.focus();
          return false;
      }
    }
    if (element.value != "" && argument.indexOf('n') != -1) // numeric
    {
      test = element.value.replace(",", "."); 
      if (isNaN(test))
      {
        alert(wrongformat +" \""+ args[i] +"\"");
        element.focus();
        return false;
      }
    }
    if (element.value != "" && argument.indexOf('a') != -1) // account
    {
      //regexp = /^[\d\s-]*$/;
           regexp = /^[a-zA-Z\s-]*[\d\s-]+$/;
      if (element.value.match(regexp) == null)
      {
        alert(wrongformat +" \""+ args[i] +"\"");
        element.focus();
        return false;
      }
    }
    if (element.value != "" && argument.indexOf('v') != -1) // vin
    {
      if (element.value.length == 17) {
        regexp = /^\w{11}\d{6}$/;
        if (element.value.match(regexp) == null)
        {
          alert(wrongformat +" \""+ args[i] +"\"");
          element.focus();
          return false;
        }      
      } else {
        alert(wrongformat +" \""+ args[i] +"\"");
        element.focus();
        return false;
      }
    }
    if (element.value != "" && argument.indexOf('p') != -1) // post
    {
      regexp = /^\d{2}-\d{3}$/;
      if (element.value.match(regexp) == null)
      {
        alert(wrongformat +" \""+ args[i] +"\"");
        element.focus();
        return false;
      }
    }
    if (element.value != "" && argument.indexOf('e') != -1) // e-mail
    {
      regexp = /^[\w.]*@[\w.]*$/;
      if (element.value.match(regexp) == null)
      {
        alert(wrongformat +" \""+ args[i] +"\"");
        element.focus();
        return false;
      }
    }
  }
}
