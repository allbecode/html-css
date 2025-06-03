// Script genérico para focar no primeiro campo de qualquer formulário.
// =======================================================================
//
// document.addEventListener('DOMContentLoaded', function () {
//     const form = document.querySelector('form');
//     if (!form) return;

//     // Seleciona o primeiro campo interativo do formulário
//     const primeiroCampo = form.querySelector('input, select, textarea');
//     if (primeiroCampo && typeof primeiroCampo.focus === 'function') {
//         primeiroCampo.focus();

//         // Se for input ou textarea, seleciona o conteúdo também
//         if (['text', 'number', 'email', 'search', 'tel', 'url'].includes(primeiroCampo.type) || primeiroCampo.tagName === 'TEXTAREA') {
//             primeiroCampo.select();
//         }
//     }
// });

// Se você quiser aplicar isso apenas a formulários específicos, substitua:

// const form = document.querySelector('form');

// por algo mais específico, como:

// const form = document.querySelector('#form-transacao');

// ou

// const form = document.querySelector('form[data-auto-focus]');

// E no HTML:

// <form id="form-transacao" data-auto-focus>

//===========================================================================
// Script para aplicar a mesma lógica a um formulário controlado por AJAX
// ===========================================================================

// function focarPrimeiroCampo(formSelector = 'form') {
//     const form = document.querySelector(formSelector);
//     if (!form) return;

//     const primeiroCampo = form.querySelector('input, select, textarea');
//     if (primeiroCampo && typeof primeiroCampo.focus === 'function') {
//         primeiroCampo.focus();

//         if (['text', 'number', 'email', 'search', 'tel', 'url'].includes(primeiroCampo.type) || primeiroCampo.tagName === 'TEXTAREA') {
//             primeiroCampo.select();
//         }
//     }
// }

// ===========================================================================
// Versão final com setTimeout (delay de segurança)
// ===========================================================================

function focarPrimeiroCampo(formSelector = 'form', delay = 50) {
    setTimeout(() => {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const primeiroCampo = form.querySelector('input, select, textarea');
        if (primeiroCampo && typeof primeiroCampo.focus === 'function') {
            primeiroCampo.focus();

            if (
                ['text', 'number', 'email', 'search', 'tel', 'url'].includes(primeiroCampo.type) ||
                primeiroCampo.tagName === 'TEXTAREA'
            ) {
                primeiroCampo.select();
            }
        }
    }, delay);
}


// Chame essa função depois que o AJAX concluir:

// ...
// Chama o foco no novo campo após a reinicialização
//        focarPrimeiroCampo('#form-transacao');
// ...
