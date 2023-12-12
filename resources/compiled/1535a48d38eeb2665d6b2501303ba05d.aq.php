<?php
$logo = resource('public/img/logo.png');
?>
<?php $__AQUA_VARIABLES["slot"]["components.layouts.layout"] = <<<HTML
<nav>
            <div class="logo">
                <img src=$logo alt="Logo">
            </div>
            <div class="navigation">
                <ul>
                    <li class="active">Home</li>
                    <li>Tests</li>
                    <li>Diagrams</li>
                    <li>Sign In</li>
                </ul>
            </div>
        </nav>
        <div class="surveys">
            <span class="title">Surveys:</span>
            <div class="list">
                <div class="survey">
                    <div class="cover">
                        <img src="public/img/survey.webp" alt="">
                    </div>
                    <div class="info">
                        <span>Solve the test!!!</span>
                        <p>
                            Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.
                        </p>
                        <button>Solve the test</button>
                    </div>
                </div>
                <div class="survey">
                    <div class="cover">
                        <img src="public/img/survey.webp" alt="">
                    </div>
                    <div class="info">
                        <span>Solve the test!!!</span>
                        <p>
                            Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.
                        </p>
                        <button>Solve the test</button>
                    </div>
                </div>
            </div>
        </div>
HTML; ?>
<?php if (getSlot($__AQUA_VARIABLES, "components.layouts.layout")) view("components.layouts.layout", ["config" => $config, "slot" => ["components.layouts.layout", $__AQUA_VARIABLES["slot"]["components.layouts.layout"]]]); else view("components.layouts.layout", ["config" => $config]); ?>