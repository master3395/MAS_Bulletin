<div class="pageoverflow mas-bl-about">
  <h2>{$mas_bl_mod->Lang('tab_about')|escape:'html'}</h2>
  <p class="information">{$mas_bl_mod->Lang('about_revision_date')|escape:'html'}</p>
  <p><strong>{$mas_bl_mod->Lang('friendlyname')|escape:'html'}</strong> - {$mas_bl_mod->GetVersion()|escape:'html'}</p>
  <p>{$mas_bl_mod->Lang('moddescription')|escape:'html'}</p>
  <p>{$mas_bl_mod->Lang('about_author')|escape:'html'} {$mas_bl_mod->GetAuthor()|escape:'html'}</p>
  <p><a href="{$mas_bl_mod->GetAuthorUrl()|escape:'htmlall'}" target="_blank" rel="noopener noreferrer">{$mas_bl_mod->GetAuthorUrl()|escape:'html'}</a></p>
</div>
