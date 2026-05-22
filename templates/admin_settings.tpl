<div class="pageoverflow mas-bl-admin-settings">
  {if $mas_banner_url|default:'' != ''}
  <img src="{$mas_banner_url|escape:'html'}" alt="" width="600" height="120" style="max-width:100%;height:auto;border-radius:6px;margin-bottom:12px;">
  {/if}
  <h2>{$mas_bl_title|escape:'html'}</h2>
  <p>{$mas_bl_hint|escape:'html'}</p>
  <link rel="stylesheet" href="{$mas_bl_codemirror_css|escape:'htmlall'}" crossorigin="anonymous" />
  {$mas_bl_form_start}
  <fieldset>
    <legend>{$mas_bl_legend_breaking|escape:'html'} (red bar on homepage)</legend>
    <p>{$mas_bl_cb_show_breaking}</p>
    <p>{$mas_bl_cb_scroll_breaking}</p>
    <p>{$mas_bl_breaking_area}</p>
  </fieldset>
  <fieldset>
    <legend>{$mas_bl_legend_live|escape:'html'} (blue bar on homepage)</legend>
    <p>{$mas_bl_cb_show_live}</p>
    <p>{$mas_bl_cb_scroll_live}</p>
    <p>{$mas_bl_live_area}</p>
  </fieldset>
  <fieldset>
    <legend>{$mas_bl_legend_links|escape:'html'}</legend>
    <p class="pagetext">{$mas_bl_help_links|escape:'html'}</p>
    <p>{$mas_bl_cb_breaking_link_news}</p>
    <p>{$mas_bl_cb_breaking_link_external}</p>
    <p>{$mas_bl_cb_live_link_news}</p>
    <p>{$mas_bl_cb_live_link_external}</p>
  </fieldset>
  <fieldset>
    <legend>{$mas_bl_legend_display|escape:'html'}</legend>
    <p><label for="bxslider_pause_ms">{$mas_bl_label_bxslider_pause|escape:'html'}</label> {$mas_bl_input_bxslider_pause}</p>
    <p><label for="marquee_breaking_s">{$mas_bl_label_marquee_breaking|escape:'html'}</label> {$mas_bl_input_marquee_breaking}</p>
    <p><label for="marquee_live_s">{$mas_bl_label_marquee_live|escape:'html'}</label> {$mas_bl_input_marquee_live}</p>
  </fieldset>
  <fieldset>
    <legend>{$mas_bl_legend_admin|escape:'html'}</legend>
    <p>{$mas_bl_help_admin|escape:'html'}</p>
    <p>{$mas_bl_input_admin_section}</p>
  </fieldset>
  <p>{$mas_bl_submit}</p>
  {$mas_bl_form_end}
</div>
<script src="{$mas_bl_codemirror_js|escape:'htmlall'}" crossorigin="anonymous"></script>
<script>
(function () {
  function boot() {
    if (typeof CodeMirror === 'undefined') return;
    document.querySelectorAll('textarea.mas-bl-textarea').forEach(function (ta) {
      if (ta.dataset.masBlCm === '1') return;
      ta.dataset.masBlCm = '1';
      CodeMirror.fromTextArea(ta, { lineNumbers: true, lineWrapping: true, indentUnit: 2, tabSize: 2 });
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
</script>
