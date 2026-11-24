<?php
// Главная страница: данные формируются из БД
/**
 * Контроллер главной страницы с подборкой ближайших мероприятий и статистикой.
 */
class HomeController
{
    /**
     * Показывает главную страницу с ближайшими мероприятиями, категориями и счётчиками.
     *
     * @return void
     */
    public function index()
    {
        render('home', [
            'title'      => 'Главная',
            'upcoming'   => Event::upcoming(6),
            'categories' => Category::withCounts(),
            'eventsCnt'  => Event::count(),
            'usersCnt'   => User::count(),
        ]);
    }
}
