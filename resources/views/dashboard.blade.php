@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de Bord - Fitness Center</h1>

    <div class="row">
        <!-- بطاقة عدد المنخرطين -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Clients</h5>
                    <p class="card-text fs-2">{{ $totalClients }}</p>
                </div>
            </div>
        </div>

        <!-- بطاقة عدد المدربين -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Coachs</h5>
                    <p class="card-text fs-2">{{ $totalCoachs }}</p>
                </div>
            </div>
        </div>

        <!-- بطاقة إجمالي الحصص -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Séances</h5>
                    <p class="card-text fs-2">{{ $totalSeances }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3>Séances d'aujourd'hui</h3>
            <table class="table table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Heure</th>
                        <th>Type</th>
                        <th>Coach</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todaySeances as $seance)
                    <tr>
                        <td>{{ $seance->heure_seance }}</td>
                        <td>{{ $seance->type_seance->nom }}</td>
                        <td>{{ $seance->coach->user->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Aucune séance prévue pour aujourd'hui.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection