// Test script to verify button functionality
console.log('Testing CURP validation button fix...');

// Test 1: Check if global functions are available
if (typeof returnToRegistrationWithData === 'function') {
    console.log('✅ returnToRegistrationWithData function is globally available');
} else {
    console.log('❌ returnToRegistrationWithData function not found');
}

// Test 2: Check if global variables are set
console.log('fromRegistry:', window.fromRegistry);
console.log('validatedCurpData:', window.validatedCurpData);

// Test 3: Mock validation data
window.validatedCurpData = {
    success: true,
    data: {
        curp: 'RICJ830716HTSSNN05',
        valid: true,
        details: {
            nombres: 'RICARDO JAVIER',
            primerApellido: 'RIVERA',
            segundoApellido: 'CASTRO',
            fechaNacimiento: '1983-07-16',
            sexo: 'MASCULINO',
            entidadNacimiento: 'SINALOA'
        }
    }
};

console.log('✅ Test data set. Button should now work.');
console.log('Click the green "Completar Registro" button to test.');