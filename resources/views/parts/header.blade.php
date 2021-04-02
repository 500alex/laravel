<div>
    <div class="header-block">
        <div class="header-block__top">
            <div class="header-top">
                <div class="header-top-right-buttons">
                    <a href="{{  route('admin')  }}">
                        <div class="admin"><i class="fa fa-user" aria-hidden="true"></i></div>
                    </a>
                </div>

            </div>
        </div>
        <div class="header-block__bottom">
            <div class="container bottom-header">
                <div class="logo">LOGO</div>
                <div class="bottom-header__catalog">
                    <div is="app-menu"></div>
                </div>

                <div class="global-search">
                    <form action="#" method="get" class="search-form">
                        <div class="global-search__search">
                            <input type="text" name="global-search" placeholder="Найти...">
                        </div>
                        <div class="global-search__button">
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>

                </div>
                <div class="right-buttons">
                    <div class="right-buttons__item">
                        <div class="button-cart">
                            <div class="button-cart__icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
                            <div class="button-cart__text">Корзина</div>
                        </div>
                    </div>
                    <div class="right-buttons__item">
                        <a href="#">
                            <div class="button-chosen">
                                <div class="button-chosen__icon"><i class="fa fa-heart" aria-hidden="true"></i></div>
                                <div class="button-chosen__text">Избранное</div>
                            </div>
                        </a>
                    </div>
                    <div class="right-buttons__item">
                        <a href="#">
                            <div class="button-match">
                                <div class="button-match__icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></div>
                                <div class="button-match__text">Сравнение</div>
                            </div>
                        </a>

                    </div>
                    <div class="right-buttons__item">
                        <button type="button" class="login-button" data-bs-toggle="modal" data-bs-target="#login">
                            <div class="button-login">
                                <div class="button-login__icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                                <div class="button-login__text">Войти</div>
                            </div>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="dialogs-block">
        <div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Вход</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Логин:</label>
                                <input type="text" class="form-control" id="recipient-login">
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Пароль:</label>
                                <input type="text" class="form-control" id="recipient-password">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Войти</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
