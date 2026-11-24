<?php
// Главная страница: данные формируются из БД
class HomeController
{
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
