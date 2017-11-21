import payform from 'payform';

export default function( form ) {

    if ( !form ) {
        return false;
    }

    const cardField  =  document.getElementById('omg-forms-card_number');
    const cardInput  =  cardField.querySelector('input');

    const cardMonth  =  document.getElementById('omg-forms-expiration_month');
    let cardMonthSelect;
    if ( cardMonth ) {
        cardMonthSelect = cardMonth.querySelector('select');
    }

    const cardYear   =  document.getElementById('omg-forms-expiration_year');
    let cardYearSelect;
    if ( cardYear ) {
         cardYearSelect = cardYear.querySelector('select');
    }

    const cardCode   = document.getElementById('omg-forms-card_code');
    const cardCodeInput = cardCode.querySelector('input');

    const cardExpiration = document.getElementById('omg-forms-expiration_date');
    let cardExpirationInput;
    if ( cardExpiration ) {
        cardExpirationInput = cardExpiration.querySelector('input');
    }

    payform.cardNumberInput(cardInput);
    payform.cvcInput(cardCodeInput);

    cardInput.addEventListener('keyup', function(event){
        if ( cardField.classList.contains('error') ) {
            validateCardInput(cardInput, payform.validateCardNumber(event.target.value), event);
        }
    });

    cardCodeInput.addEventListener('keyup', function(event){
        if ( cardCode.classList.contains('error') ) {
            validateCardInput(cardCodeInput, payform.validateCardCVC(event.target.value), event);
        }
    });

    cardInput.addEventListener('blur', function () {
        validateCardInput(cardInput, payform.validateCardNumber(event.target.value), event)
    });

    cardCodeInput.addEventListener('blur', function () {
        validateCardInput(cardCodeInput, payform.validateCardCVC(event.target.value), event)
    });

    if ( cardExpiration ) {
        cardExpirationInput.addEventListener('keyup', function(event){
            if ( cardCode.classList.contains('error') ) {
                validateCardInput(cardCodeInput, payform.validateCardCVC(event.target.value), event);
            }
        });

        cardExpirationInput.addEventListener('blur', function () {
            validateCardInput(cardInput, payform.validateCardNumber(event.target.value), event)
        });
    }

    if ( cardMonth ) {
        cardMonthSelect.addEventListener('change', function () {
            validateCardDate()
        });
    }

    if ( cardYear ) {
        cardYearSelect.addEventListener('change', function () {
            validateCardDate()
        });
    }

    // [].forEach.call( cardDate, function( item ) {
    //     let select = item.querySelector('select');
    //
    //     select.addEventListener('change', function () {
    //         validateCardDate()
    //     });
    // });

    function validateCardInput(element, valid, event) {

        if ( valid === false && !event.target.value == '' ) {
            let errorMessage = cardField.querySelector('.omg-error');
            element.parentNode.classList.add('error');
            errorMessage.innerHTML = 'This card number is not valid';

        } else if ( event.target.value == '' || valid === true  ) {
            element.parentNode.classList.remove('error');
        }
    }

    function validateCardDate() {
        let month = cardMonthSelect.value;
        let year  = cardYearSelect.value;
        let validCardDate = payform.validateCardExpiry( month, year);

        if ( validCardDate === false && !month == '' && !year == '' ) {
            cardMonth.classList.add('error');
            cardYear.classList.add('error');

        } else if ( validCardDate === true || month == '' && year == ''   ) {
            cardMonth.classList.remove('error');
            cardYear.classList.remove('error');
        }
    }
}
