import payform from 'payform';

export default function( form = document.getElementById( 'donationForm' ) ) {

    if ( !form ) {
        return false;
    }

    let cardField  =  document.getElementById('omg-forms-card_number');
    let cardInput  =  cardField.querySelector('input');
    let cardDate   = document.querySelectorAll('.donation-expiration-date');
    let cardMonth  =  document.getElementById('omg-forms-expiration_month');
    let cardMonthSelect = cardMonth.querySelector('select');
    let cardYear   =  document.getElementById('omg-forms-expiration_year');
    let cardYearSelect = cardYear.querySelector('select');
    let cardCode   = document.getElementById('omg-forms-card_code');
    let cardCodeInput = cardCode.querySelector('input');

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

    [].forEach.call( cardDate, function( item ) {
        let select = item.querySelector('select');

        select.addEventListener('change', function () {
            validateCardDate()
        });
    });

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
