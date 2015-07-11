<?php
    require 'loader.php';
    global $config;
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Zweite Sprache</title>
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
            App.GetCallForSaveLanguageData = function () { return 'SaveLanguageDataAndItems.php'; };
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
            {^{emptyBrackets:~upper ? ~firstUpper(LanguageData.Data[~index].Text) : LanguageData.Data[~index].Text}}
        </script>
        <script id="quote-template" type="text/x-jsrender">
            <div class="button link" style="float: right;" onclick="reset();">
                löschen
            </div>
            <div class="poem-input">
                {{for LanguageData.Data}}
                    <div class="row">
                        <small style="width: 30px; display: inline-block;">{{:#index + 1}}</small> {{include tmpl="#item-template" /}}                        
                    </div>
                {{/for}}
                <div class="row">
                    <small style="width: 30px; display: inline-block;">11</small> 
                    <div class="input-text" style="border-color: transparent;">Winter</div>
                    Substantiv
                </div>
            </div>
            <small>bitte wörter groß-/kleinschreiben wie sie isoliert im wörterbuch ständen. die satzanfänge macht <i>zweite sprache</i> für dich</small>
            <div id="quote">
                {{include tmpl="#word-template" ~index=0 ~upper=true /}}<br>
                {{include tmpl="#word-template" ~index=1 ~upper=true /}} {{include tmpl="#word-template" ~index=2 /}}<br>
                {{include tmpl="#word-template" ~index=3 ~upper=true /}} {{include tmpl="#word-template" ~index=4 /}} {{include tmpl="#word-template" ~index=5 /}}<br>
                {{include tmpl="#word-template" ~index=6 ~upper=true /}} {{include tmpl="#word-template" ~index=7 /}} {{include tmpl="#word-template" ~index=8 /}} 
                    {{include tmpl="#word-template" ~index=9 /}}<br>
                Winter
            </div>
            <div class="button link" style="float: right; position: relative; bottom: 70px;" onclick="$.view(this).ctx.root.LanguageData.Send(reset);">
                einsenden
            </div>
        </script>       
        <script type="text/javascript"> 
            $.views.converters({
                emptyBrackets: function (str) {
                    return str && str.length > 0 ? str : "[ ]";
                }
            });
            $.views.helpers({
                onAfterChange: function (event) {
                    if (event.type === "change") {
                        var target = $.view(event.target);
                        if (target.ctx.root.DoneLoading) {
                            localStorage.setItem('Zweite/LanguageItems', target.ctx.root.LanguageData.Stringify());
                        }
                    }
                }
            });
            
            function reset() {
                localStorage.setItem('Zweite/LanguageItems', '');
                window.location.reload();
            }
            
            $(function () {
                Template = $.templates("#quote-template");
                Data = { 
                    LanguageData : new LanguageData(null, App.GetScope(), null),
                    DoneLoading : false
                };
                
                //not really necessary
                for (var i = 0; i < 10; i++) {
                    Data.LanguageData.Data.push(new LanguageItem(null, App.GetScope(), i, 0));
                }
                    
                try {
                    var storedData = new LanguageData().Populate(localStorage.getItem('Zweite/LanguageItems'));
                    if (storedData && storedData instanceof LanguageData) {
                        var items = storedData.Data.concat(); //creates deep copy
                        storedData.Data = [];
                        for (var item in items) {
                            storedData.Data.push(new LanguageItem().Populate(items[item]));
                        }
                    }
                    Data.LanguageData = storedData;
                } catch (e) {
                    console.error(e);
                }
                
                Data.DoneLoading = true;
                Template.link('#target', Data);
            });
        </script>
    </head>
    <body lang="de"> 
        <a href="../Dritte" style="float: right;">DRITTE SPRACHE →</a>
        <a href="../Erste">← ERSTE SPRACHE</a><br><br>
        <h1>zweite sprache</h1>
        <br>
        dieser teil der serie braucht eure kreativität! ihr könnt ein elfchen (siehe <a href="http://de.wikipedia.org/wiki/Elfchen">wikipedia</a>) eintragen,
        müsst es aber wort für wort und am besten mit dem typ des worts (substantiv, verb, etc.) angeben - dazu unten eine kleine liste. 
        vorgeben ist nur das thema (bzw. letzte wort): <b>winter</b>.<br>
        was das soll? die struktur der daten soll in vieler hinsicht helfen die daten zu strukturieren. durch die zusätzliche bewertung der elfchen anderer 
        in der <i>dritten sprache</i> (kommt in wenigen tagen) kann (unter umständen) ein algorithmus gefunden werden, der sich sinnvollen elfchen automatisiert annähert - aber
        nur wenn ihr ihm helft!
        
        <div id="target"></div>
        <br><br>
        KLEINE DEUTSCHSTUNDE (BEISPIELE)
        <table style="width: 100%;">
            <?php foreach (WordType::loop() as $word_type) { ?>
                <tr>
                    <td style="width: 25%; font-weight: 900; text-transform: lowercase;"><?php echo $word_type->get_name(); ?></td>
                    <td><?php echo $word_type->get_example(); ?></td>
                </tr>
            <?php } ?>
        </table>
        <br>
        DATEN BESTIMMUNGEN<br>
        es werden alle eingaben gespeichert, dass ist ja der sinn dieser seite. darüber hinaus werden keine daten gespeichert. die daten werden anonym gespeichert.
        bis du auf "einsenden" klickst, werden die daten nur auf deinem computer <i>(js: localStorage)</i> gespeichert. diese können mit "neu" gelöscht werden.
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