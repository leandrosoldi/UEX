window.openLoadOverlay = function() {
  document.getElementById("load-overlay").style.display = "block";
};

window.closeLoadOverlay = function() {
    document.getElementById("load-overlay").style.display = "none";
};

window.validaCpf = function (valor) {
  if (!valor) {
      return false;
  }

  // Garante que o valor é uma string
  valor = valor.toString();

  // Remove caracteres inválidos do valor
  valor = valor.replace(/[^0-9]/g, '');


  // Captura os 9 primeiros dígitos do CPF
  // Ex.: 02546288423 = 025462884
  var digitos = valor.substr(0, 9);

  // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
  var novo_cpf = calcDigitosPosicoes(digitos);

  // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
  var novo_cpf = calcDigitosPosicoes(novo_cpf, 11);

  // Verifica se o novo CPF gerado é idêntico ao CPF enviado
  if (novo_cpf === valor) {
      // CPF válido
      return true;
  } else {
      // CPF inválido
      return false;
  }

};  

window.calcDigitosPosicoes = function (digitos, posicoes = 10, soma_digitos = 0) {

  // Garante que o valor é uma string
  digitos = digitos.toString();

  // Faz a soma dos dígitos com a posição
  // Ex. para 10 posições:
  //   0    2    5    4    6    2    8    8   4
  // x10   x9   x8   x7   x6   x5   x4   x3  x2
  //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
  for (var i = 0; i < digitos.length; i++) {
      // Preenche a soma com o dígito vezes a posição
      soma_digitos = soma_digitos + (digitos[i] * posicoes);

      // Subtrai 1 da posição
      posicoes--;

      // Parte específica para CNPJ
      // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
      if (posicoes < 2) {
          // Retorno a posição para 9
          posicoes = 9;
      }
  }

  // Captura o resto da divisão entre soma_digitos dividido por 11
  // Ex.: 196 % 11 = 9
  soma_digitos = soma_digitos % 11;

  // Verifica se soma_digitos é menor que 2
  if (soma_digitos < 2) {
      // soma_digitos agora será zero
      soma_digitos = 0;
  } else {
      // Se for maior que 2, o resultado é 11 menos soma_digitos
      // Ex.: 11 - 9 = 2
      // Nosso dígito procurado é 2
      soma_digitos = 11 - soma_digitos;
  }

  // Concatena mais um dígito aos primeiro nove dígitos
  // Ex.: 025462884 + 2 = 0254628842
  var cpf = digitos + soma_digitos;

  // Retorna
  return cpf;

};

window.formatarCPF = function(str) {
  return str.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
};

window.formatarTelefone = function(str) {
  return str.replace(/^(\d{2})(\d{4,5})(\d{4})$/, '($1) $2-$3');
};
