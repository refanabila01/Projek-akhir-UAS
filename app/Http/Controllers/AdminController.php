<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use App\Models\Country;
use App\Models\News;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Admin.
     */
    public function dashboard()
    {
        // Seed mock users dynamically if they do not exist
        $mocks = [
            ['name' => 'Refa Nabila', 'email' => 'refa@gmail.com', 'role' => 'user'],
            ['name' => 'Dimas Pratama', 'email' => 'dimas@gmail.com', 'role' => 'user'],
            ['name' => 'Anisa Rahma', 'email' => 'anisa@gmail.com', 'role' => 'user'],
        ];
        foreach ($mocks as $m) {
            if (!User::where('email', $m['email'])->exists()) {
                User::create([
                    'name' => $m['name'],
                    'email' => $m['email'],
                    'password' => bcrypt('password123'),
                    'role' => $m['role'],
                ]);
            }
        }

        $userCount = User::count();
        $portCount = Port::count();
        $newsCount = News::count();
        $countryCount = Country::count();

        // Get users list matching user database
        $dbUsers = User::all();
        $usersList = [];
        foreach ($dbUsers as $u) {
            $usersList[] = [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role === 'admin' ? 'Admin' : 'Analis',
                'status' => 'Aktif',
            ];
        }

        // Get recent articles
        $recentArticles = News::orderBy('published_at', 'desc')->take(4)->get();

        return view('admin.index', compact(
            'userCount', 'portCount', 'newsCount', 'countryCount',
            'usersList', 'recentArticles'
        ));
    }

    /**
     * Manage users.
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Store new user.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Manage ports.
     */
    public function ports()
    {
        $ports = Port::all();
        return view('admin.ports', compact('ports'));
    }

    /**
     * Manage articles.
     */
    public function articles()
    {
        $articles = Article::with('author')->get();
        return view('admin.articles', compact('articles'));
    }

    /**
     * Update user role.
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete user.
     */
    public function destroyUser($id)
    {
        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Store new port.
     */
    public function storePort(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'country_code' => 'required|string|max:5',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'congestion_status' => 'required|in:Low,Medium,High',
            'wpi_number' => 'nullable|string|max:50',
            'region' => 'nullable|string|max:100',
            'delay_hours' => 'required|integer|min:0',
        ]);

        Port::create($request->all());

        return redirect()->back()->with('success', 'Pelabuhan berhasil ditambahkan.');
    }

    /**
     * Update port.
     */
    public function updatePort(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'country_code' => 'required|string|max:5',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'congestion_status' => 'required|in:Low,Medium,High',
            'wpi_number' => 'nullable|string|max:50',
            'region' => 'nullable|string|max:100',
            'delay_hours' => 'required|integer|min:0',
        ]);

        $port = Port::findOrFail($id);
        $port->update($request->all());

        return redirect()->back()->with('success', 'Pelabuhan berhasil diperbarui.');
    }

    /**
     * Delete port.
     */
    public function destroyPort($id)
    {
        $port = Port::findOrFail($id);
        $port->delete();

        return redirect()->back()->with('success', 'Pelabuhan berhasil dihapus.');
    }

    /**
     * Store new article.
     */
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'author_id' => auth()->id(),
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Update article.
     */
    public function updateArticle(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = Article::findOrFail($id);
        $article->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Delete article.
     */
    public function destroyArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->back()->with('success', 'Artikel berhasil dihapus.');
    }
}
