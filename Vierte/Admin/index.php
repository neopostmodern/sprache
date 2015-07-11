<?php
    require '../loader.php';
    global $config;
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Admin: Vierte Sprache</title>
        <link href="<?php echo $config->urls[0]->common[0]->images; ?>favicon.ico" rel="icon" type="image/x-icon" />
        
	<meta http-equiv="content-type" content="text/html; charset=utf-8" > 
	<meta charset="utf-8">
        <meta http-equiv="content-language" content="de">
        
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>jQuery.js"></script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>jsViews.js"></script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>BaseTemplating.js"></script>
        
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>Application.js"></script>
        <script>
            App = new Application();
            App.GetUrlForJsonApi = function () { return '<?php echo $config->urls[0]->common[0]->jsonapi; ?>'; };
            App.GetCallForSaveLanguageData = function () { return 'SaveLanguageData.php'; };
            App.GetScope = function () { return 2; };
        </script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>LanguageData.js"></script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>LanguageItem.js"></script>
        
        <?php if ($config->mode == "production") { ?>
            <link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Gentium+Basic:400italic' rel='stylesheet' type='text/css'>
        <?php } ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $config->urls[0]->common[0]->css; ?>Sprache.css" />
        <link rel="stylesheet" type="text/css" href="./Styles/Site.css" />
        
        <script id="item-template" type="text/x-jsrender">
            <input type="text" data-link=Text />
            <select data-link=Type>
                <?php foreach (WordType::loop() as $i => $word_type) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $word_type->get_name(); ?></option>                    
                <?php } ?>
            </select>
        </script>
        <script id="word-template" type="text/x-jsrender">
            {^{:~upper ? ~firstUpper(~getValue(LanguageData.Data[~index])) : ~getValue(LanguageData.Data[~index])}}
        </script>
        <script id="quote-template" type="text/x-jsrender">
            <div class="progress">
                <div class="count">{^{:Count}} von 10</div>
                <div class="bar">
                    <div class="filled" data-link="css-width{:Count * 10 + '%'}"></div>
                </div>
            </div>
            {^{if Count < 10}}
                {^{if LanguageData}}
                    <div class="link" style="float: right;" onclick="LoadLanguageData();">kenn ich schon →</div>
                    <br><br>
                    <small>
                        <b>achtung</b> solange noch wenige elfchen in der datenbank stehen, wird <i>dritte sprache</i> euch mehrfach das selbe elfchen vorschlagen,
                        teilweise sieht das so aus als hätte es eure bewertung nicht berücksichtigt - hat es aber immer.
                        verwendet den "kenn ich schon" button oder wartet noch ein bisschen, aber bitte nicht doppelt abstimmen.
                    </small>
                {{/if}}
                <div id="quote">
                    {^{if LanguageData}}
                        {{include tmpl="#word-template" ~index=0 ~upper=true /}}<br>
                        {{include tmpl="#word-template" ~index=1 ~upper=true /}} {{include tmpl="#word-template" ~index=2 /}}<br>
                        {{include tmpl="#word-template" ~index=3 ~upper=true /}} {{include tmpl="#word-template" ~index=4 /}} {{include tmpl="#word-template" ~index=5 /}}<br>
                        {{include tmpl="#word-template" ~index=6 ~upper=true /}} {{include tmpl="#word-template" ~index=7 /}} {{include tmpl="#word-template" ~index=8 /}} 
                            {{include tmpl="#word-template" ~index=9 /}}<br>
                        Winter
                    {{else}}
                        Es lädt...
                    {{/if}}
                </div>
                <div class="rating">
                    <div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', -2); SaveLanguageData();">
                        --
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', -1); SaveLanguageData();">
                        -
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', 0); SaveLanguageData();">
                        0
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', 1); SaveLanguageData();">
                        +
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', 2); SaveLanguageData();">
                        ++
                    </div>
                </div>
            {{else}}
                <div id="quote">
                    Vielen Dank für's mitmachen!<br>
                    Sobald möglich, stehen hier Ergebnisse.
                </div>
            {{/if}}
        </script>       
        <script type="text/javascript">                    
            function LoadLanguageData() {
                $.get(App.GetUrlForJsonApi() + 'GetLanguageData.php?Scope=2&CompleteResponse=true', {}, function (data) {
                    Data.DoneLoading = false;
                    
                    if (Data.LanguageData) {
                        Data.LanguageData.ObservablePopulate(data);
                    }
                    else {
                        var languageData = new LanguageData();
                        languageData.Populate(data);
                        $.observable(Data).setProperty("LanguageData", languageData);
                    }
                    
                    Data.DoneLoading = true;
                });
            };  
            function NextLanguageData() {
                $.observable(Data).setProperty("Count", Data.Count + 1); 
                localStorage.setItem('Dritte/count', Data.Count);
                LoadLanguageData();
            };
            function SaveLanguageData() {
                Data.LanguageData.Send(NextLanguageData);
            };
            
            $(function () {
                Template = $.templates("#quote-template");
                Data = { 
                    LanguageData : null,
                    Count : localStorage['Dritte/count'] ? parseInt(localStorage['Dritte/count']) : 0,
                    DoneLoading : false
                };
                        
                Data.DoneLoading = true;
                Template.link('#target', Data);
                
                LoadLanguageData();
            });
        </script>
    </head>
    <body lang="de"> 
        <a href="..">← ZUR VIERTEN SPRACHE</a><br><br>
        <h1>vierter administrator</h1>
        <br>
        dieser teil der serie braucht eure bewertungen! ihr könnt die elfchen (siehe <a href="http://de.wikipedia.org/wiki/Elfchen">wikipedia</a>), 
        die andere für die <i>zweite sprache</i> schrieben, zum thema/schlusswort <b>winter</b>
        von -2 (--) bis +2 (--) bewerten - nach euren eigenen kriterien! literatur wird ja auch nicht nach gesetz bewertet.<br>
        was das soll? die bewertung soll helfen muster aus den daten der <i>zweiten sprache</i> zu finden um den algorithmus zu verbessern - aber nur wenn ihr ihm helft!
        
        <div id="target"></div>
        <br><br>
        DATEN BESTIMMUNGEN<br>
        es werden nur die antworten gespeichert. darüber hinaus werden keine daten gespeichert. die daten werden anonym gespeichert.
        auf deinem computer <i>(js: localStorage)</i> wird die anzahl der beantworteten fragen gespeichert. dies kann nicht gelöscht werden.
        <br>
        <br>
        IMPRESSUM<br> clemens m. schöll // wolfgang-dachstein-straße 3 // 77654 offenburg // schoell@mrssheep.com
        <br>
        <br>
        <a onclick="$('#legal').slideDown(); $(this).fadeOut();">haftungsausschluss anzeigen</a>
        <small style="color: #888; display: none;" id="legal">
            <b>Haftungsausschluss</b>
            <br>
            <i>Haftung für Inhalte</i><br>
            Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.
            <br>
            <i>Haftung für Links</i><br>
            Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
            <br>
            <i>Urheberrecht</i><br>
            Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.
            <br>
            <i>Datenschutz</i><br>
            Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben. Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich. Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.
            <br>
            <i>Datenschutzerklärung für die Nutzung von Facebook-Plugins (Like-Button)</i><br>
            Auf unseren Seiten sind Plugins des sozialen Netzwerks Facebook, 1601 South California Avenue, Palo Alto, CA 94304, USA integriert. Die Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem "Like-Button" ("Gefällt mir") auf unserer Seite. Eine Übersicht über die Facebook-Plugins finden Sie hier: http://developers.facebook.com/docs/plugins/. Wenn Sie unsere Seiten besuchen, wird über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook "Like-Button" anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Facebook-Profil verlinken. Dadurch kann Facebook den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von facebook unter http://de-de.facebook.com/policy.php
            Wenn Sie nicht wünschen, dass Facebook den Besuch unserer Seiten Ihrem Facebook- Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem Facebook-Benutzerkonto aus. 
        </small> 
    </body>
</html>