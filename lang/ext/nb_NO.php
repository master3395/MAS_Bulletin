<?php

// MAS shared donations admin UI
$lang['donations_tab'] = 'Donasjoner';
$lang['donate_btn'] = 'Doner';
$lang['donations_sponsor_logo_alt'] = 'News Targeted-logo';
$lang['donations_sponsor_link'] = 'Besøk NewsTargeted';
$lang['showdonationstab'] = 'Vis fanen Donasjoner';
$lang['show_donations_tab'] = 'Vis fanen Donasjoner';
$lang['show_donations_tab_help'] = 'Når den er av, skjules Donasjoner til du slår den på igjen i modulinnstillingene.';
$lang['donations_tab_hidden'] = 'Donasjonsfanen er skjult. Du kan vise den igjen i modulinnstillingene.';
if (!function_exists('cmsms')) {
    exit;
}

$lang['friendlyname'] = 'MAS Breaking og Live';
$lang['moddescription'] = 'Breaking- og live-ticker på forsiden, valgfrie nyhetstitler fra News-modulen, marquee-hastighet og pause for bxSlider på forsiden.';
$lang['help'] = '<p>Plasser <code>{MAS_BreakingLive}</code> tidlig i forsidesmalen (for eksempel MarqueePageHome) slik at slider-timing brukes.</p><p>Åpne <strong>Utvidelser → MAS Breaking og Live</strong> for faner, nyhetsrekkefølge og visningstider.</p>';
$lang['changelog'] = 'Se CHANGELOG.md i modulmappen (full historikk).';
$lang['postinstall'] = 'Gi gruppen som skal styre tickerne tilgangen «Administrer MAS_BreakingLive», og åpne deretter modulen for å sette tekster.';
$lang['postuninstall'] = 'MAS_BreakingLive er avinstallert.';
$lang['really_uninstall'] = 'Vil du fjerne MAS_BreakingLive og alle innstillingene?';
$lang['help_action'] = 'Reservert til senere bruk.';
$lang['accessdenied'] = 'Ingen tilgang.';
$lang['settings_title'] = 'Breaking- og Live-tickere';
$lang['label_breaking'] = 'Breaking-linje';
$lang['label_live'] = 'Live-linje';
$lang['label_show_breaking'] = 'Vis breaking-felt';
$lang['label_show_live'] = 'Vis live-felt';
$lang['label_scroll_breaking'] = 'Animer breaking-linjen når den er lang';
$lang['label_scroll_live'] = 'Animer live-linjen når den er lang';
$lang['submit'] = 'Lagre';
$lang['saved'] = 'Innstillingene er lagret.';
$lang['hint_lines'] = 'Bruk ren tekst. Kuletegn • mellom punkter er greit. Hold linjene korte for mobil. CodeMirror er bare for lesbarhet.';
$lang['tab_settings'] = 'Innstillinger';
$lang['tab_news'] = 'Nyhetskilder';
$lang['tab_help'] = 'Hjelp';
$lang['tab_about'] = 'Om';
$lang['tab_changelog'] = 'Endringslogg';
$lang['tab_donations'] = 'Donasjoner';
$lang['legend_display'] = 'Timing på forsiden';
$lang['label_bxslider_pause'] = 'Pause for nyhetsslider på forsiden (millisekunder, 2000-120000)';
$lang['label_marquee_breaking'] = 'Varighet for breaking-marquee (sekunder, 8-180)';
$lang['label_marquee_live'] = 'Varighet for live-marquee (sekunder, 8-180)';
$lang['title_admin_section'] = 'Plassering i admin-meny';
$lang['help_admin_section'] = 'Velg hvor modulen vises i CMS-admin (samme nøkler som MAS_CSR).';
$lang['label_merge_mode'] = 'Sammenslåingsmodus';
$lang['merge_manual_only'] = 'Kun manuell tekst';
$lang['merge_news_only'] = 'Kun nyhetstitler (fall tilbake til manuell hvis tom)';
$lang['merge_news_first'] = 'Nyhetstitler først, deretter manuell tekst';
$lang['merge_manual_first'] = 'Manuell tekst først, deretter nyhetstitler';
$lang['news_intro'] = 'Velg publiserte nyheter fra News for hver linje. Rekkefølge settes med dra og slipp. Krever News-modulen. Hvis News mangler, brukes bare manuell tekst.';
$lang['opt_pick_article'] = 'Velg en artikkel';
$lang['btn_add_article'] = 'Legg til';
$lang['help_tab_body'] = '<p><strong>Tagg:</strong> <code>{MAS_BreakingLive}</code> skriver ut breaking- og live-feltene og setter <code>window.__NT_BX_SLIDER_PAUSE_MS__</code> for bxSlider på forsiden.</p><p><strong>News:</strong> sammenslåingsmodus styrer hvordan manuell tekst kombineres med titler i valgt rekkefølge.</p><p><strong>Avinstallering:</strong> viser modulen «Kan ikke fjernes», kjør <strong>Utvidelser → oppgradering</strong> til minst <strong>1.1.1</strong> (normaliserer rettigheter), eller rett eierskap på <code>modules/MAS_BreakingLive</code> slik at PHP-brukeren eier filene, eller slett mappen over SSH.</p>';
$lang['help_tab_plain'] = 'Kort referanse for redaktører.';
$lang['help_li_tag'] = 'Plasser taggen nær toppen av forsidesmalen slik at slideren leser pause før init.';
$lang['help_li_news'] = 'Bruk fanen Nyheter for å legge til artikler og rekkefølge; titler vises i ticker når modus tillater det.';
$lang['help_li_bxslider'] = 'Slider-pause og marquee-sekunder valideres på serveren.';
$lang['help_li_uninstall'] = '«Kan ikke fjernes» betyr ofte at webbrukeren ikke kan skrive til alle filer under modulen (CMSMS sjekker rekursivt). Oppgradering til 1.1.1+ retter vanlig 644 mot 660; ellers må PHP-pool-bruker og eierskap stemme.';
$lang['about_author'] = 'Forfatter:';
$lang['about_revision_date'] = 'Sist oppdatert: 03.05.2026.';
$lang['changelog_revision_date'] = 'Per 03.05.2026.';
$lang['donationstab'] = 'Donasjoner';
$lang['donationstext'] = 'Hvis modulen sparer deg for tid, vurder en liten donasjon via PayPal. Takk for at du støtter News Targeted.';
$lang['sponsors'] = 'Takk til alle som støtter utviklingen.';
$lang['hidedonationssubmit'] = 'Skjul donasjonsfane';
