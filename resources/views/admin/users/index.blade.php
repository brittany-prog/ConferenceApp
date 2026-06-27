@extends('layouts.app')

@section('title', 'Users | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell">
    @php
        $adminCount = $users->where('is_admin', true)->count();
        $speakerCount = $users->where('is_speaker', true)->count();
        $exhibitorCount = $users->where('is_exhibitor', true)->count();
        $activeCount = $users->where('can_login', true)->count();
    @endphp

    <section class="panel stack admin-users-shell">
        <div>
            <span class="eyebrow">Access Control</span>
            <h2 style="margin: 10px 0 0;">Users</h2>
            <p class="lede" style="margin:0;">Add attendees, manage exhibitors, and keep admin access clean without touching the database directly.</p>
        </div>

        <div class="admin-users-summary">
            <article class="card card-loud admin-users-summary-card">
                <p class="muted">In view</p>
                <h3 class="metric-number">{{ $users->count() }}</h3>
            </article>
            <article class="card card-loud admin-users-summary-card">
                <p class="muted">Can log in</p>
                <h3 class="metric-number">{{ $activeCount }}</h3>
            </article>
            <article class="card card-loud admin-users-summary-card">
                <p class="muted">Admins</p>
                <h3 class="metric-number">{{ $adminCount }}</h3>
            </article>
            <article class="card card-loud admin-users-summary-card">
                <p class="muted">Exhibitors</p>
                <h3 class="metric-number">{{ $exhibitorCount }}</h3>
            </article>
        </div>

        <div class="admin-users-layout">
            <aside class="stack admin-users-sidebar">
                <form method="GET" action="/admin/users" class="card stack admin-users-card">
                    <div>
                        <span class="eyebrow">Filter</span>
                        <h3 style="margin:10px 0 0;">Find the right people faster</h3>
                        <p class="muted" style="margin:8px 0 0;">Search by name, email, title, or organization and narrow the list by role or login status.</p>
                    </div>

                    <div class="stack" style="gap:12px;">
                        <div>
                            <label for="user-search">Search</label>
                            <input id="user-search" type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, organization...">
                        </div>
                        <div>
                            <label for="user-role">Role</label>
                            <select id="user-role" name="role">
                                <option value="">All roles</option>
                                <option value="attendee" @selected(request('role') === 'attendee')>Attendee</option>
                                <option value="speaker" @selected(request('role') === 'speaker')>Speaker</option>
                                <option value="exhibitor" @selected(request('role') === 'exhibitor')>Exhibitor</option>
                                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            </select>
                        </div>
                        <div>
                            <label for="user-status">Login status</label>
                            <select id="user-status" name="status">
                                <option value="">All statuses</option>
                                <option value="active" @selected(request('status') === 'active')>Can log in</option>
                                <option value="disabled" @selected(request('status') === 'disabled')>Disabled</option>
                            </select>
                        </div>
                    </div>

                    <div class="admin-users-sidebar-actions">
                        <a href="/admin/users" class="button secondary">Reset</a>
                        <a
                            href="{{ route('admin.users.export', request()->only(['q', 'role', 'status'])) }}"
                            class="button secondary"
                        >
                            Download CSV
                        </a>
                        <button type="submit" class="button">Apply filters</button>
                    </div>
                </form>

                <form method="POST" action="/admin/users" class="card stack admin-users-card">
                    @csrf

                    <div>
                        <span class="eyebrow">Add User</span>
                        <h3 style="margin:10px 0 0;">Create a user</h3>
                        <p class="muted" style="margin:8px 0 0;">Use this for invited attendees, speakers, exhibitors, staff, or admins who should not go through public registration.</p>
                    </div>

                    <div class="stack" style="gap:12px;">
                        <div>
                            <label for="new-user-name">Name</label>
                            <input id="new-user-name" type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label for="new-user-email">Email</label>
                            <input id="new-user-email" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div>
                            <label for="new-user-title">Title</label>
                            <input id="new-user-title" type="text" name="title" value="{{ old('title') }}">
                        </div>
                        <div>
                            <label for="new-user-organization">Organization</label>
                            <input id="new-user-organization" type="text" name="organization" value="{{ old('organization') }}">
                        </div>
                    </div>

                    <div class="admin-user-checkboxes">
                        <label class="check-row">
                            <input type="checkbox" name="can_login" value="1" @checked(old('can_login', true))>
                            <span>Can log in</span>
                        </label>
                        <label class="check-row">
                            <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin'))>
                            <span>Admin access</span>
                        </label>
                        <label class="check-row">
                            <input type="checkbox" name="is_speaker" value="1" @checked(old('is_speaker'))>
                            <span>Speaker profile</span>
                        </label>
                        <label class="check-row">
                            <input type="checkbox" name="is_exhibitor" value="1" @checked(old('is_exhibitor'))>
                            <span>Exhibitor profile</span>
                        </label>
                    </div>

                    <div>
                        <label for="new-user-sponsor">Linked sponsor / exhibitor company</label>
                        <select id="new-user-sponsor" name="sponsor_id">
                            <option value="">No sponsor linked</option>
                            @foreach ($sponsors as $sponsor)
                                <option value="{{ $sponsor->id }}" @selected((string) old('sponsor_id') === (string) $sponsor->id)>{{ $sponsor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <p class="muted" style="margin:0;">The user will get a secure account setup email after you create them.</p>

                    <button type="submit" class="button">Create user</button>
                </form>

                <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="card stack admin-users-card">
                    @csrf

                    <div>
                        <span class="eyebrow">Bulk Import</span>
                        <h3 style="margin:10px 0 0;">Import attendees by CSV</h3>
                        <p class="muted" style="margin:8px 0 0;">Upload a CSV with at least <strong>name</strong> and <strong>email</strong>. Optional columns: title, organization, can_login, is_speaker, is_exhibitor, is_admin, sponsor, or sponsor_id.</p>
                    </div>

                    <div class="stack" style="gap:12px;">
                        <div>
                            <label for="attendee-import-file">CSV file</label>
                            <input id="attendee-import-file" type="file" name="csv_file" accept=".csv,text/csv">
                            <p class="field-help" style="margin:8px 0 0;">Use <code>yes</code>/<code>no</code>, <code>true</code>/<code>false</code>, or <code>1</code>/<code>0</code> for the optional role and login columns.</p>
                        </div>
                    </div>

                    <div class="admin-user-checkboxes">
                        <label class="check-row">
                            <input type="checkbox" name="update_existing" value="1">
                            <span>Update existing users by email</span>
                        </label>
                        <label class="check-row">
                            <input type="checkbox" name="send_password_setup" value="1">
                            <span>Send account setup emails</span>
                        </label>
                    </div>

                    <div class="admin-users-sidebar-actions">
                        <a href="{{ route('admin.users.import-template') }}" class="button secondary">Download template</a>
                        <button type="submit" class="button">Import CSV</button>
                    </div>
                </form>
            </aside>

            <div class="stack">
                @forelse ($users as $user)
                    <form method="POST" action="/admin/users/{{ $user->id }}" class="card stack admin-user-record">
                        @csrf
                        @method('PUT')

                        <div class="admin-user-record__header">
                            <div>
                                <h3 style="margin:0 0 8px;">{{ $user->name }}</h3>
                                <div class="meta-stack">
                                    @if ($user->is_admin)
                                        <span class="meta-pill">Admin</span>
                                    @endif
                                    @if ($user->is_speaker)
                                        <span class="meta-pill">Speaker</span>
                                    @endif
                                    @if ($user->is_exhibitor)
                                        <span class="meta-pill">Exhibitor</span>
                                    @endif
                                    @if (! $user->can_login)
                                        <span class="meta-pill">Login disabled</span>
                                    @endif
                                </div>
                            </div>
                            <p class="muted" style="margin:0;">Created {{ $user->created_at?->format('M j, Y') ?? 'recently' }}</p>
                        </div>

                        <div class="grid grid-2">
                            <div>
                                <label for="name-{{ $user->id }}">Name</label>
                                <input id="name-{{ $user->id }}" type="text" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div>
                                <label for="email-{{ $user->id }}">Email</label>
                                <input id="email-{{ $user->id }}" type="email" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div>
                                <label for="title-{{ $user->id }}">Title</label>
                                <input id="title-{{ $user->id }}" type="text" name="title" value="{{ $user->title }}">
                            </div>
                            <div>
                                <label for="organization-{{ $user->id }}">Organization</label>
                                <input id="organization-{{ $user->id }}" type="text" name="organization" value="{{ $user->organization }}">
                            </div>
                        </div>

                        <div class="admin-user-checkboxes">
                            <label class="check-row">
                                <input type="checkbox" name="can_login" value="1" @checked($user->can_login)>
                                <span>Can log in</span>
                            </label>
                            <label class="check-row">
                                <input type="checkbox" name="is_admin" value="1" @checked($user->is_admin)>
                                <span>Admin access</span>
                            </label>
                            <label class="check-row">
                                <input type="checkbox" name="is_speaker" value="1" @checked($user->is_speaker)>
                                <span>Speaker profile</span>
                            </label>
                            <label class="check-row">
                                <input type="checkbox" name="is_exhibitor" value="1" @checked($user->is_exhibitor)>
                                <span>Exhibitor profile</span>
                            </label>
                        </div>

                        <div class="grid grid-2">
                            <div>
                                <label for="sponsor-{{ $user->id }}">Linked sponsor / exhibitor company</label>
                                <select id="sponsor-{{ $user->id }}" name="sponsor_id">
                                    <option value="">No sponsor linked</option>
                                    @foreach ($sponsors as $sponsor)
                                        <option value="{{ $sponsor->id }}" @selected((string) $user->sponsor_id === (string) $sponsor->id)>{{ $sponsor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="admin-user-setup-toggle">
                                <label class="check-row">
                                    <input type="checkbox" name="send_password_setup" value="1">
                                    <span>Email account setup link</span>
                                </label>
                            </div>
                        </div>

                        <div class="admin-user-record__actions">
                            <button type="submit" class="button secondary">Save changes</button>
                            <button type="submit" class="button secondary" form="delete-user-{{ $user->id }}" onclick="return confirm('Delete this user?')">Delete user</button>
                        </div>
                    </form>
                    <form id="delete-user-{{ $user->id }}" method="POST" action="/admin/users/{{ $user->id }}" class="inline-form">
                        @csrf
                        @method('DELETE')
                    </form>
                @empty
                    <div class="card">
                        <p class="muted" style="margin:0;">No users yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    </div>

    @push('page-styles')
        <style>
            .admin-users-shell {
                gap: 22px;
            }

            .admin-users-summary {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 14px;
            }

            .admin-users-summary-card {
                padding: 18px;
                backdrop-filter: blur(10px);
                min-height: 100%;
            }

            .admin-users-layout {
                display: grid;
                grid-template-columns: 340px minmax(0, 1fr);
                gap: 20px;
                align-items: start;
            }

            .admin-users-sidebar {
                position: sticky;
                top: 18px;
            }

            .admin-users-card {
                padding: 20px;
                backdrop-filter: blur(10px);
            }

            .admin-users-sidebar-actions,
            .admin-user-record__actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .admin-user-record {
                gap: 18px;
                padding: 22px;
                backdrop-filter: blur(10px);
            }

            .admin-user-record__header {
                display: flex;
                justify-content: space-between;
                gap: 16px;
                align-items: flex-start;
                flex-wrap: wrap;
                padding-bottom: 2px;
            }

            .admin-user-checkboxes {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px 14px;
            }

            .admin-user-setup-toggle {
                display: flex;
                align-items: end;
            }

            .admin-user-record .meta-pill,
            .admin-users-summary-card .muted {
                font-weight: 650;
            }

            .admin-user-record__actions .button.secondary:first-child {
                background: rgba(24, 49, 83, 0.08);
                border-color: rgba(24, 49, 83, 0.12);
            }

            @media (max-width: 980px) {
                .admin-users-summary {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .admin-users-layout {
                    grid-template-columns: 1fr;
                }

                .admin-users-sidebar {
                    position: static;
                }
            }

            @media (max-width: 720px) {
                .admin-users-summary,
                .admin-user-checkboxes {
                    grid-template-columns: 1fr;
                }

                .admin-users-sidebar-actions .button,
                .admin-user-record__actions .button {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    @endpush
@endsection
