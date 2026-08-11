@extends('layouts.app')

@section('content')
<div class="content-area">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        General Report - Customer Service
                    </h2>
                    <a href="{{ route('service.index') }}" class="ml-auto flex text-theme-1"> 
                        <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data 
                    </a>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('service.antrean', 'baru') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="user-plus" class="report-box__icon text-blue-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-1 tooltip cursor-pointer" title="Transaksi Baru - {{ $stats['baru'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['baru'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Transaksi Baru</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('service.antrean', 'proses') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="user-check" class="report-box__icon text-green-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1 tooltip cursor-pointer" title="Transaksi diproses - {{ $stats['proses'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['proses'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Transaksi Diproses</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('service.antrean', 'konfirmasi') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="phone-outgoing" class="report-box__icon text-yellow-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full mt-1 tooltip cursor-pointer" title="Konfirmasi - {{ $stats['konfirmasi'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['konfirmasi'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Konfirmasi Harga</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('service.antrean', 'pelunasan') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="credit-card" class="report-box__icon text-red-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mt-1 tooltip cursor-pointer" title="Pelunasan - {{ $stats['pelunasan'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['pelunasan'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Pelunasan</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('service.antrean', 'lunas') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="check-circle" class="report-box__icon text-blue-700 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-blue-700 rounded-full mt-1 tooltip cursor-pointer" title="Selesai (Lunas) - {{ $stats['lunas'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['lunas'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Selesai / Lunas</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: General Report -->          
        </div>
    </div>
</div>
@endsection
