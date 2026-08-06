@extends('layouts.admin')

@section('breadcrumb', 'Customers \u203A Create Customer')
@section('page_title', 'Create New Customer')

@section('content')
<div x-data="memberCreateForm()" class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.users.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm text-primary-600 dark:text-primary-400">
        Create a new customer with comprehensive information
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Form Area -->
    <div class="lg:col-span-3">
      <div class="glass p-6 rounded-2xl">
        <form id="customerForm" @submit.prevent="saveCustomer" class="space-y-8">
          @csrf
          
          <!-- Basic Information -->
          <div>
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-user text-primary-500 text-xs"></i> Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">First Name *</label>
                <input type="text" name="first_name" required class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Middle Name</label>
                <input type="text" name="middle_name" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Last Name *</label>
                <input type="text" name="last_name" required class="form-input">
              </div>
              <div class="md:col-span-3">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Email Address *</label>
                <input type="email" name="email_address" required class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Gender *</label>
                <select name="gender" required class="form-input">
                  <option value="">Select gender...</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">National ID (NIDA)</label>
                <input type="text" name="national_id" class="form-input font-mono">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Passport/Driving License</label>
                <input type="text" name="passport_driving_license" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Registration Date *</label>
                <input type="date" name="registration_date" required class="form-input" value="{{ now()->format('Y-m-d') }}">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Status *</label>
                <select name="status" required class="form-input">
                  <option value="pending">Pending</option>
                  <option value="active">Active</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-address-book text-primary-500 text-xs"></i> Contact Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Phone Number</label>
                <input type="text" name="phone_number" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Alternative Phone</label>
                <input type="text" name="alternative_phone" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Region</label>
                <input type="text" name="region" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">District</label>
                <input type="text" name="district" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Ward</label>
                <input type="text" name="ward" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Street/Village</label>
                <input type="text" name="street_village" class="form-input">
              </div>
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Physical Address</label>
                <textarea name="physical_address" rows="2" class="form-input"></textarea>
              </div>
            </div>
          </div>

          <!-- Next of Kin -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-users text-primary-500 text-xs"></i> Next of Kin
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Full Name</label>
                <input type="text" name="kin_full_name" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Relationship</label>
                <input type="text" name="kin_relationship" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Phone Number</label>
                <input type="text" name="kin_phone_number" class="form-input">
              </div>
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Address</label>
                <textarea name="kin_address" rows="2" class="form-input"></textarea>
              </div>
            </div>
          </div>

          <!-- Documents -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-file text-primary-500 text-xs"></i> Documents
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Passport Photo</label>
                <input type="file" name="passport_photo" accept="image/*" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">National ID Copy</label>
                <input type="file" name="national_id_copy" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Signature</label>
                <input type="file" name="signature" accept="image/*" class="form-input">
              </div>
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Other Attachments</label>
                <input type="file" name="other_attachments[]" multiple class="form-input">
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-note-sticky text-primary-500 text-xs"></i> Additional Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Notes</label>
                <textarea name="notes" rows="4" class="form-input"></textarea>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Tags (comma separated)</label>
                <input type="text" name="tags" class="form-input" placeholder="e.g. VIP, Corporate">
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold transition-all">
              Cancel
            </a>
            <button type="submit" :disabled="loading" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed">
              <i :class="loading ? 'fa-solid fa-spinner fa-spin' : 'fa-solid fa-save'" class="mr-1.5"></i>
              <span x-text="loading ? 'Creating Customer...' : 'Create Customer'">Create Customer</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Sidebar Summary -->
    <div class="lg:col-span-1">
      <div class="glass p-6 rounded-2xl sticky top-6">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
          <i class="fa-solid fa-clipboard-list text-primary-500 text-xs"></i> Summary
        </h3>
        <div class="space-y-4">
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Customer Number</p>
            <p class="text-sm font-mono font-semibold text-primary-900 dark:text-white">Auto-generated</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Registration Date</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white">{{ now()->format('Y-m-d') }}</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Customer Type</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white">Standard</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Status</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white">Pending</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function memberCreateForm() {
  return {
    loading: false,
    
    async saveCustomer() {
      this.loading = true;
      const form = document.getElementById('customerForm');
      const formData = new FormData(form);
      
      try {
        const response = await fetch('{{ route('admin.users.store') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const contentType = response.headers.get('content-type');
        
        if (!response.ok) {
          if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            let errorMessage = 'Failed to create customer.';
            if (data.errors) {
              const errorMessages = Object.values(data.errors).flat();
              errorMessage = errorMessages.join('\n');
            } else if (data.message) {
              errorMessage = data.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: errorMessage
            });
          } else {
            const text = await response.text();
            console.error('Server returned HTML instead of JSON:', text);
            Swal.fire({
              icon: 'error',
              title: 'Server Error',
              text: 'Please check the form for validation errors.'
            });
          }
          this.loading = false;
          return;
        }
        
        const data = await response.json();
        
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Customer created successfully.',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            window.location.href = '{{ route('admin.users.index') }}';
          });
        } else {
          let errorMessage = 'Failed to create customer.';
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            errorMessage = errorMessages.join('\n');
          } else if (data.message) {
            errorMessage = data.message;
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage
          });
          this.loading = false;
        }
      } catch (error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.message || 'Failed to create customer.'
        });
        this.loading = false;
      }
    }
  };
}
</script>
@endpush
@endsection
