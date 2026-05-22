<style>
.mas-bl-sort-list { list-style: none; margin: 0.5rem 0; padding: 0; max-width: 720px; }
.mas-bl-sort-item { padding: 0.35rem 0.5rem; margin: 0.25rem 0; border: 1px solid #ccc; background: #fafafa; cursor: grab; display: flex; align-items: center; justify-content: space-between; }
.mas-bl-dragging { opacity: 0.6; }
</style>
<div class="pageoverflow mas-bl-admin-news">
  <h2>{$mas_bl_news_title|escape:'html'}</h2>
  {if !$mas_bl_news_installed}
    <p class="information">{$mas_bl_news_intro|escape:'html'}</p>
  {else}
    <p>{$mas_bl_news_intro|escape:'html'}</p>
  {/if}
  {$mas_bl_news_form_start}
  <fieldset>
    <legend>{$mas_bl_legend_links|escape:'html'}</legend>
    <p class="pagetext">{$mas_bl_help_links_news|escape:'html'}</p>
    <p>{$mas_bl_cb_breaking_link_news}</p>
    <p>{$mas_bl_cb_breaking_link_external}</p>
    <p>{$mas_bl_cb_live_link_news}</p>
    <p>{$mas_bl_cb_live_link_external}</p>
  </fieldset>
  <fieldset>
    <legend>{$mas_bl_legend_breaking|escape:'html'}</legend>
    <p><label>{$mas_bl_label_merge|escape:'html'}</label> {$mas_bl_breaking_merge_select}</p>
    <p>{$mas_bl_hidden_breaking_order}</p>
    <ul id="mas-bl-breaking-sort" class="mas-bl-sort-list"></ul>
    <p>
      <select id="mas-bl-breaking-add-select">
        <option value="">{$mas_bl_opt_pick|escape:'html'}</option>
        {foreach from=$mas_bl_picker_articles item=pa}
          <option value="{$pa.id|escape:'htmlall'}">{$pa.title|escape:'html'}</option>
        {/foreach}
      </select>
      <button type="button" class="cms_button" id="mas-bl-breaking-add">{$mas_bl_btn_add|escape:'html'}</button>
    </p>
  </fieldset>
  <fieldset>
    <legend>{$mas_bl_legend_live|escape:'html'}</legend>
    <p><label>{$mas_bl_label_merge|escape:'html'}</label> {$mas_bl_live_merge_select}</p>
    <p>{$mas_bl_hidden_live_order}</p>
    <ul id="mas-bl-live-sort" class="mas-bl-sort-list"></ul>
    <p>
      <select id="mas-bl-live-add-select">
        <option value="">{$mas_bl_opt_pick|escape:'html'}</option>
        {foreach from=$mas_bl_picker_articles item=pa}
          <option value="{$pa.id|escape:'htmlall'}">{$pa.title|escape:'html'}</option>
        {/foreach}
      </select>
      <button type="button" class="cms_button" id="mas-bl-live-add">{$mas_bl_btn_add|escape:'html'}</button>
    </p>
  </fieldset>
  <p>{$mas_bl_news_submit}</p>
  {$mas_bl_news_form_end}
</div>
<script>
(function () {
  var breakingIds = {$mas_bl_breaking_ids_json nofilter};
  var liveIds = {$mas_bl_live_ids_json nofilter};
  var titlesBreak = {$mas_bl_breaking_titles_json nofilter};
  var titlesLive = {$mas_bl_live_titles_json nofilter};

  function syncHidden(ul, input) {
    if (!input) return;
    var ids = [];
    ul.querySelectorAll('li[data-id]').forEach(function (li) { ids.push(li.getAttribute('data-id')); });
    input.value = ids.join(',');
  }

  function wireList(ul, hidden) {
    ul.addEventListener('dragover', function (e) { e.preventDefault(); });
    ul.addEventListener('drop', function (e) {
      e.preventDefault();
      var dragId = e.dataTransfer.getData('text/plain');
      var dragged = ul.querySelector('li[data-id="' + dragId + '"]');
      var over = e.target.closest('li[data-id]');
      if (!dragged || !over || dragged === over) return;
      var rect = over.getBoundingClientRect();
      var before = e.clientY < rect.top + rect.height / 2;
      if (before) {
        ul.insertBefore(dragged, over);
      } else {
        ul.insertBefore(dragged, over.nextSibling);
      }
      syncHidden(ul, hidden);
    });
  }

  function renderList(ul, hidden, ids, titles) {
    ul.innerHTML = '';
    ids.forEach(function (id) {
      var sid = String(id);
      var li = document.createElement('li');
      li.setAttribute('data-id', sid);
      li.draggable = true;
      li.className = 'mas-bl-sort-item';
      var span = document.createElement('span');
      span.textContent = titles[sid] || titles[id] || ('#' + sid);
      var rm = document.createElement('button');
      rm.type = 'button';
      rm.className = 'cms_button';
      rm.textContent = '×';
      rm.style.marginLeft = '8px';
      rm.addEventListener('click', function () {
        var idStr = li.getAttribute('data-id');
        var num = parseInt(idStr, 10);
        var ix = ids.findIndex(function (x) { return parseInt(x, 10) === num || String(x) === idStr; });
        if (ix >= 0) ids.splice(ix, 1);
        li.remove();
        syncHidden(ul, hidden);
      });
      li.addEventListener('dragstart', function (e) {
        e.dataTransfer.setData('text/plain', sid);
        li.classList.add('mas-bl-dragging');
      });
      li.addEventListener('dragend', function () { li.classList.remove('mas-bl-dragging'); });
      li.appendChild(span);
      li.appendChild(rm);
      ul.appendChild(li);
    });
    syncHidden(ul, hidden);
  }

  document.addEventListener('DOMContentLoaded', function () {
    var hb = document.querySelector('input[name*="breaking_article_order"]');
    var hl = document.querySelector('input[name*="live_article_order"]');
    var ub = document.getElementById('mas-bl-breaking-sort');
    var ul = document.getElementById('mas-bl-live-sort');
    if (!hb || !hl || !ub || !ul) return;
    var bIds = Array.isArray(breakingIds) ? breakingIds.map(function (x) { return parseInt(x, 10); }).filter(function (n) { return n > 0; }) : [];
    var lIds = Array.isArray(liveIds) ? liveIds.map(function (x) { return parseInt(x, 10); }).filter(function (n) { return n > 0; }) : [];
    wireList(ub, hb);
    wireList(ul, hl);
    renderList(ub, hb, bIds, titlesBreak);
    renderList(ul, hl, lIds, titlesLive);

    document.getElementById('mas-bl-breaking-add').addEventListener('click', function () {
      var sel = document.getElementById('mas-bl-breaking-add-select');
      var id = parseInt(sel.value, 10);
      if (!id || bIds.indexOf(id) !== -1) return;
      var opt = sel.options[sel.selectedIndex];
      titlesBreak[String(id)] = opt ? opt.text : ('#' + id);
      bIds.push(id);
      renderList(ub, hb, bIds, titlesBreak);
      sel.value = '';
    });
    document.getElementById('mas-bl-live-add').addEventListener('click', function () {
      var sel = document.getElementById('mas-bl-live-add-select');
      var id = parseInt(sel.value, 10);
      if (!id || lIds.indexOf(id) !== -1) return;
      var opt = sel.options[sel.selectedIndex];
      titlesLive[String(id)] = opt ? opt.text : ('#' + id);
      lIds.push(id);
      renderList(ul, hl, lIds, titlesLive);
      sel.value = '';
    });
  });
})();
</script>
