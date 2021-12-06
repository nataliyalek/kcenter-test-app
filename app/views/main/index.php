<h1>Главная страница</h1>


<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Подписка на сранение цен</div>
        <div class="card-body">
            <form action="/priceinfoapi/subscribe" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email">
                </div>
                <div class="form-group">
                    <label>url</label>
                    <input class="form-control" type="text" name="url">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Подписаться</button>
            </form>
        </div>
    </div>
</div>