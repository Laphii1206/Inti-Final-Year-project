<?php

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::create([
        'name'          => 'Admin Test',
        'email'         => 'admin@example.com',
        'phone'         => '012-3456789',
        'password'      => bcrypt('password'),
        'role'          => User::ROLE_ADMIN,
        'is_main_admin' => true,
    ]);

    $this->mechanic = User::create([
        'name'          => 'Mechanic Test',
        'email'         => 'mechanic@example.com',
        'phone'         => '012-7654321',
        'password'      => bcrypt('password'),
        'role'          => User::ROLE_MECHANIC,
    ]);

    $this->customer = User::create([
        'name'          => 'Customer Test',
        'email'         => 'customer@example.com',
        'phone'         => '012-1111111',
        'password'      => bcrypt('password'),
        'role'          => User::ROLE_CUSTOMER,
    ]);

    $this->car = Car::create([
        'user_id'   => $this->customer->id,
        'brand'     => 'Toyota',
        'model'     => 'Vios',
        'year'      => 2020,
        'car_plate' => 'ABC1234',
        'mileage'   => 10000,
    ]);

    $this->branch = Branch::create([
        'name'             => 'Test Branch',
        'contact_number'   => '012345678',
        'address'          => '123 Test St',
        'opening_time'     => '09:00',
        'closing_time'     => '18:00',
        'service_capacity' => 3,
        'is_active'        => true,
    ]);

    $this->service = Service::create([
        'branch_id'          => $this->branch->id,
        'name'               => 'Test Service',
        'category'           => 'engine oil',
        'price'              => 100.00,
        'estimated_duration' => 60,
        'is_active'          => true,
    ]);
});

test('admin cannot change unpaid booking status to in_progress or completed', function () {
    $booking = Booking::create([
        'user_id'                  => $this->customer->id,
        'car_id'                   => $this->car->id,
        'service_id'               => $this->service->id,
        'branch_id'                => $this->branch->id,
        'booking_date'             => now()->toDateString(),
        'start_time'               => '10:00:00',
        'end_time'                 => '11:00:00',
        'status'                   => Booking::STATUS_CONFIRMED,
        'service_price_at_booking' => 100.00,
    ]);

    $this->actingAs($this->admin);

    // Try changing to in_progress
    $response = $this->put(route('admin.bookings.update', $booking->uuid), [
        'status' => Booking::STATUS_IN_PROGRESS,
    ]);
    $response->assertSessionHas('error');
    expect($booking->fresh()->status)->toBe(Booking::STATUS_CONFIRMED);

    // Try changing to completed
    $response = $this->put(route('admin.bookings.update', $booking->uuid), [
        'status' => Booking::STATUS_COMPLETED,
    ]);
    $response->assertSessionHas('error');
    expect($booking->fresh()->status)->toBe(Booking::STATUS_CONFIRMED);
});

test('admin cannot complete unpaid in_progress booking via complete endpoint', function () {
    $booking = Booking::create([
        'user_id'                  => $this->customer->id,
        'car_id'                   => $this->car->id,
        'service_id'               => $this->service->id,
        'branch_id'                => $this->branch->id,
        'booking_date'             => now()->toDateString(),
        'start_time'               => '10:00:00',
        'end_time'                 => '11:00:00',
        'status'                   => Booking::STATUS_IN_PROGRESS,
        'service_price_at_booking' => 100.00,
    ]);

    $this->actingAs($this->admin);

    $response = $this->post(route('admin.bookings.complete', $booking->uuid));
    $response->assertSessionHas('error');
    expect($booking->fresh()->status)->toBe(Booking::STATUS_IN_PROGRESS);
});

test('mechanic cannot complete unpaid in_progress job', function () {
    $booking = Booking::create([
        'user_id'                  => $this->customer->id,
        'car_id'                   => $this->car->id,
        'service_id'               => $this->service->id,
        'branch_id'                => $this->branch->id,
        'assigned_staff_id'        => $this->mechanic->id,
        'booking_date'             => now()->toDateString(),
        'start_time'               => '10:00:00',
        'end_time'                 => '11:00:00',
        'status'                   => Booking::STATUS_IN_PROGRESS,
        'service_price_at_booking' => 100.00,
    ]);

    $this->actingAs($this->mechanic);

    $response = $this->post(route('mechanic.jobs.complete', $booking->uuid), [
        'recorded_mileage' => 15000,
    ]);

    $response->assertRedirect(route('mechanic.jobs.index'));
    $response->assertSessionHas('error');
    expect($booking->fresh()->status)->toBe(Booking::STATUS_IN_PROGRESS);
});

test('paid booking can be marked as in_progress and completed', function () {
    $booking = Booking::create([
        'user_id'                  => $this->customer->id,
        'car_id'                   => $this->car->id,
        'service_id'               => $this->service->id,
        'branch_id'                => $this->branch->id,
        'assigned_staff_id'        => $this->mechanic->id,
        'booking_date'             => now()->toDateString(),
        'start_time'               => '10:00:00',
        'end_time'                 => '11:00:00',
        'status'                   => Booking::STATUS_CONFIRMED,
        'service_price_at_booking' => 100.00,
    ]);

    Payment::create([
        'booking_id' => $booking->id,
        'user_id'    => $this->customer->id,
        'gateway'    => 'stripe',
        'amount'     => 100.00,
        'currency'   => 'MYR',
        'status'     => Payment::STATUS_PAID,
        'paid_at'    => now(),
    ]);

    $this->actingAs($this->admin);

    // Update to in_progress
    $response = $this->put(route('admin.bookings.update', $booking->uuid), [
        'status' => Booking::STATUS_IN_PROGRESS,
    ]);
    $response->assertSessionHas('success');
    expect($booking->fresh()->status)->toBe(Booking::STATUS_IN_PROGRESS);

    // Complete job by mechanic
    $this->actingAs($this->mechanic);
    $response = $this->post(route('mechanic.jobs.complete', $booking->uuid), [
        'recorded_mileage' => 15000,
    ]);
    $response->assertSessionHas('success');
    expect($booking->fresh()->status)->toBe(Booking::STATUS_COMPLETED);
});
