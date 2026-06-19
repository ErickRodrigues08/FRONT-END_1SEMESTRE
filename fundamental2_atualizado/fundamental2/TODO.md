# TODO

- [ ] Atualizar o formulário público: filtrar modalidades por gênero (masculino -> handebol_masculino; feminino -> handebol_feminino) e manter “volei_misto” visível para ambos.


- [ ] Atualizar lógica JS do formulário público (`assets/js/cadastro.js`) para esconder/desabilitar/desmarcar modalidades incompatíveis ao trocar o gênero.

- [ ] Atualizar modal de edição no admin para aplicar a mesma regra ao trocar o gênero (`admin/dashboard.php` + `assets/js/dashboard.js`).

- [ ] (Recomendado) Enforçar compatibilidade no backend (`api/register.php` e `api/students.php`) validando modalidades X gênero.
- [ ] Testar fluxo completo: cadastro (masc/fem), limite de 2 modalidades, e edição no admin.

