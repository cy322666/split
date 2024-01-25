<div class="container">
    <div class="d-flex align-items-center min-vh-100">
        <div class="mx-auto md-auto">

                <form action="form/send" method="POST" class="col-4 p-5 mb-2 bg-light mx-auto">
                    @csrf <!-- {{ csrf_field() }} -->
{{--                <form wire:submit.prevent="save" class="col-4 p-5 mb-2 bg-light mx-auto">--}}
                <div>
                    @if (session()->has('message'))
                        <div class="alert alert-danger">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
                <div class="text-center mb-4">

                    <h1 class="h4 mb-3 font-weight-normal"><strong>Оплата частями</strong></h1>
                    <p class="text-sm-start"><small>После нажатия кнопки вы перейдете на сервис оплаты в рассрочку "Яндекс.Сплит"
                        Обратите внимание: при выборе варианта оплаты с рассрочкой на 4 месяца и более,
                        сервис Яндекс.Сплит будет взимать с вас дополнительную комиссию.
                        Это внутренняя комиссия Яндекс.Сплит, которая не имеет отношения к TutGood.

                        Если не желаете ее оплачивать, пожалуйста, выбирайте вариант с оплатой в течение 2 месяцев.</small></p>
                </div>

                <div class="mb-4">
                    <div class="form-label-group">
                        <input name="email" id="email" type="email" class="form-control" placeholder="Email" required autofocus="">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-label-group">
                        <input name="phone" id="phone" type="text" class="form-control" placeholder="Номер телефона" required>
                    </div>
                </div>
                <p class="text-sm-start mb-0"><small>Оплата в течение 2 месяцев:</small></p>
                <p class="text-sm-start mb-0"><small>
                    - Четыре платежа по одному разу в 2 недели
                </small></p>
                <p class="text-sm-start"><small>
                    - Комиссия Яндекс.Сплит - без комиссии
                </small></p>
                <p class="text-sm-start mb-0"><small>Оплата в течение 4 месяцев:</small></p>
                <p class="text-sm-start mb-0"><small>
                    - Четыре платежа - раз в месяц
                </small></p>
                <p class="text-sm-start"><small>
                    - Комиссия Яндекс.Сплит - 5%
                </small></p>
                <p class="text-sm-start mb-0"><small>Оплата в течение 6 месяцев:</small></p>
                <p class="text-sm-start mb-0"><small>
                    - Шесть платежей - раз в месяц
                </small></p>
                <p class="text-sm-start mb-4"><small>
                    - Комиссия Яндекс.Сплит - 10%
                </small></p>
                <div class="form-row text-center mb-3">
{{--                    <div class="col-12">--}}
                        <button class="form-row text-center btn btn-sl btn-primary btn-block" type="submit">Оплатить частями</button>
                </div>

                <div class="form-row text-center mb-0">
                    <p id="demo"></p>

                    <script>
                        var today = new Date();
                        var hh = today.getHours();
                        var mm = today.getMinutes();
                        var ss = today.getSeconds();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mmm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();

                        var newDate = new Date(yyyy, mmm, dd, hh, mm + 20, ss);

                        var countDownDate = newDate.toISOString().replace('T', ' ').replace('Z', '');

                        console.log(countDownDate);

                        // Обновляйте обратный отсчет каждые 1 секунду
                        var x = setInterval(function() {

                            // Получить сегодняшнюю дату и время
                            var now = new Date().getTime();

                            var sub = new Date(countDownDate).getTime();

                            // Найти расстояние между сейчас и датой обратного отсчета
                            var distance = sub - now;

                            // Расчет времени по дням, часам, минутам и секундам
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            document.getElementById("demo").innerHTML = minutes + ":" + seconds;

                            // Если обратный отсчет завершен, напишите текст
                            if (distance < 0) {
                                clearInterval(x);
                                document.getElementById("demo").innerHTML = "Истекло";
                            }
                        }, 1000);
                    </script>
                </div>
            </form>
        </div>
    </div>
</div>
