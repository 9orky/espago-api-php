<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <script src="https://code.jquery.com/jquery-latest.js">
    </script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js">
    </script>
    <script src='https://js.espago.com/espago-1.1.js' type='text/javascript'>
    </script>
    <script>
        $(document).ready(function(){
                $("#espago_form").submit(function(event){
                        var espago = new Espago({
                            public_key: '4kdYkSxSY2t9APLBQw9x', custom: true, live: false, api_version: '3'}
                        );
                        event.preventDefault();
                        if (espago.validate_card_number()){
                            //obsługa komunikatów wyniku walidacji numeru karty
                            $("#espago_card_number_error").text("");
                        }
                        else {
                            $("#espago_card_number_error").text("Błędne dane!");
                        };
                        if (espago.validate_first_name()){
                            //obsługa komunikatów wyniku walidacji imienia
                            $("#espago_first_name_error").text("");
                        }
                        else {
                            $("#espago_first_name_error").text("Błędne dane!");
                        };
                        if (espago.validate_last_name()){
                            //obsługa komunikatów wyniku walidacji nazwiska
                            $("#espago_last_name_error").text("");
                        }
                        else {
                            $("#espago_last_name_error").text("Błędne dane!");
                        };
                        if (espago.validate_card_date()){
                            ///obsługa komunikatów wyniku walidacji daty wygasania karty
                            $("#espago_year_error").text("");
                        }
                        else {
                            $("#espago_year_error").text("Błędne dane!");
                        };
                        if (espago.validate_card_cvc()){
                            //obsługa komunikatów wyniku walidacji wartości CVC 
                            $("#espago_verification_value_error").text("");
                        }
                        else {
                            $("#espago_verification_value_error").text("Błędne dane!");
                        };
                        espago.create_token();
                    }
                );
            }
        );
    </script>
</head>
<body>
<form id='espago_form' method='POST' action=''>
    <label>Credit Card Number
    </label>
    <input id='espago_card_number' type='text'/>
      <span id='espago_card_number_error'>
      </span>
    <br />
    <label>First Name
    </label>
    <input id='espago_first_name' type='text'/>
      <span id='espago_first_name_error'>
      </span>
    <br />
    <label>Last Name
    </label>
    <input id='espago_last_name' type='text'/>
      <span id='espago_last_name_error'>
      </span>
    <br />
    <label>Month expire
    </label>
    <input id='espago_month' type='text'/>
      <span id='espago_month_error'>
      </span>
    <br />
    <label>Year expire
    </label>
    <input id='espago_year' type='text'/>
      <span id='espago_year_error'>
      </span>
    <br />
    <label>Verification Value
    </label>
    <input id='espago_verification_value' type='text'/>
      <span id='espago_verification_value_error'>
      </span>
    <input type='submit' value='Go'/>
</form>
</body>
</html>