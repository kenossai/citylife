<div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px;">
        <div style="display: flex; align-items: center;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; margin-right: 16px;">
                {{ strtoupper(substr($record->name, 0, 1)) }}
            </div>
            <div>
                <h2 style="margin: 0; font-size: 18px; font-weight: 600; color: #111827;">{{ $record->name }}</h2>
                <p style="margin: 2px 0 0 0; font-size: 14px; color: #6b7280;">{{ $record->email }}</p>
            </div>
        </div>
        <div style="text-align: right;">
            <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 16px; font-size: 12px; font-weight: 500;">
                {{ ucfirst(str_replace('_', ' ', $record->status)) }}
            </span>
            <p style="margin: 4px 0 0 0; font-size: 12px; color: #9ca3af;">
                {{ $record->created_at->format('M d, Y g:i A') }}
            </p>
        </div>
    </div>
    
    <!-- Subject -->
    <div style="margin-bottom: 16px;">
        <h3 style="margin: 0; font-size: 20px; font-weight: 500; color: #111827;">{{ $record->subject }}</h3>
    </div>
    
    <!-- Message -->
    <div style="margin-bottom: 20px;">
        <div style="color: #374151; line-height: 1.6; white-space: pre-line;">{{ $record->message }}</div>
    </div>
    
    @if($record->phone)
    <!-- Phone -->
    <div style="padding-top: 16px; border-top: 1px solid #e5e7eb;">
        <p style="margin: 0; color: #6b7280; font-size: 14px;">
            <strong>Phone:</strong> {{ $record->phone }}
        </p>
    </div>
    @endif
    
    <!-- Footer -->
    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af;">
        <div style="display: flex; justify-content: space-between;">
            <span>Message ID: #{{ $record->id }}</span>
            <span>IP: {{ $record->ip_address ?? 'N/A' }}</span>
        </div>
    </div>
</div>
