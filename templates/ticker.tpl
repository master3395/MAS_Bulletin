{* Sets home bxSlider pause (read in MarqueePageHome footerjs) and optional marquee duration. *}
<script>
(function (n) {
  var v = parseInt(n, 10);
  if (!isFinite(v) || v < 2000) v = 10000;
  if (v > 120000) v = 120000;
  window.__NT_BX_SLIDER_PAUSE_MS__ = v;
})({$mas_bl_bxslider_pause_ms|default:10000|intval});
</script>
{if $mas_bl_show_breaking && $mas_bl_breaking|default:'' != ''}
<div class="nt-news-v2-breaking-banner mas-bl-breaking{if $mas_bl_scroll_breaking} mas-bl-has-scroll{/if}" role="region" aria-label="Breaking news">
  <div class="nt-news-v2-breaking-content">
    <span class="nt-news-v2-breaking-label"><i class="fas fa-bolt" aria-hidden="true"></i> Breaking News</span>
    {if $mas_bl_scroll_breaking}
    <div class="mas-bl-text-outer mas-bl-marquee-outer mas-bl-marquee-active" role="presentation" data-mas-bl-scroll="1" data-marquee-duration="{$mas_bl_marquee_breaking_s_fmt|default:'48.00'|escape:'htmlall'}">
      <div class="mas-bl-marquee-track" data-marquee-duration="{$mas_bl_marquee_breaking_s_fmt|default:'48.00'|escape:'htmlall'}">
        <span class="nt-news-v2-breaking-text mas-bl-line mas-bl-marquee-content">{$mas_bl_breaking nofilter}</span>
      </div>
    </div>
    {else}
    <span class="nt-news-v2-breaking-text mas-bl-line">{$mas_bl_breaking nofilter}</span>
    {/if}
  </div>
</div>
{/if}
{if $mas_bl_show_live && $mas_bl_live|default:'' != ''}
<div class="nt-news-v2-live-ticker mas-bl-live{if $mas_bl_scroll_live} mas-bl-has-scroll{/if}" role="status" aria-live="polite">
  <div class="nt-news-v2-live-viewport">
    <div class="nt-news-v2-live-content">
      <span class="nt-news-v2-live-label"><span class="nt-news-v2-live-dot" aria-hidden="true"></span> Live</span>
      {if $mas_bl_scroll_live}
      <div class="mas-bl-text-outer mas-bl-marquee-outer mas-bl-live-grow mas-bl-marquee-active" role="presentation" data-mas-bl-scroll="1" data-marquee-duration="{$mas_bl_marquee_live_s_fmt|default:'56.00'|escape:'htmlall'}">
        <div class="mas-bl-marquee-track mas-bl-marquee-track--live" data-marquee-duration="{$mas_bl_marquee_live_s_fmt|default:'56.00'|escape:'htmlall'}">
          <span class="nt-news-v2-live-text mas-bl-line mas-bl-marquee-content">{$mas_bl_live nofilter}</span>
        </div>
      </div>
      {else}
      <span class="nt-news-v2-live-text mas-bl-line mas-bl-live-grow">{$mas_bl_live nofilter}</span>
      {/if}
    </div>
  </div>
</div>
{/if}
{if ($mas_bl_scroll_breaking && $mas_bl_breaking|default:'' != '') || ($mas_bl_scroll_live && $mas_bl_live|default:'' != '')}
<script src="{root_url}/modules/MAS_Bulletin/lib/mas_bl_marquee.js?v=20260525" defer></script>
{/if}
