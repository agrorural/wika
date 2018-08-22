<form id="ajaxSearch" action="">
  <div id="searchContainer" class="input-group input-group-lg">
    <input id="searchInput" type="text" class="form-control" placeholder="Ingrese su búsqueda" aria-label="Recipient's username with two button addons" aria-describedby="button-addon4">
    <a id="backspaceTrigger" href="#!" style="display: none"><i class="fas fa-backspace"></i></a>
    <div class="input-group-append" id="button-addon4">
      <select class="custom-select" id="cboPostType">
        <option value="['post', 'knowledgebase', 'forum', 'faq']" selected>En todo</option>
        <option value="knowledgebase">En la wiki</option>
        <option value="forum">En los foros</option>
        <option value="faq">En Preguntas</option>
        <option value="post">En entradas</option>
      </select>
    </div>
  </div>
</form>