import cardValidation from './card-validation';

document.addEventListener( 'DOMContentLoaded', () => {
    const forms = document.querySelectorAll( '.omg-form-wrapper' );

    if ( forms ) {
        [].forEach.call( forms, function( formWrapper ) {
            const formTypes = JSON.parse( formWrapper.dataset.formtype );

            if ( -1 !== formTypes.indexOf('authorize_net') ) {
                cardValidation( formWrapper.querySelector( 'form' ) );
            }
        } );

    }

} );
