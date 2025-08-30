<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Traits;

use Illuminate\Support\Facades\Auth;

trait AuditablePivotTrait
{
    /**
     * Override the attach method to automatically set audit fields
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        // Add audit fields to the attributes
        $attributes = $this->addAuditFieldsToPivot($attributes, true);
        
        return parent::attach($id, $attributes, $touch);
    }

    /**
     * Override the detach method - no audit fields needed as row is deleted
     */
    public function detach($ids = null, $touch = true)
    {
        return parent::detach($ids, $touch);
    }

    /**
     * Override the sync method to automatically set audit fields
     */
    public function sync($ids, $detaching = true)
    {
        // If $ids is an array with attributes, add audit fields
        if (is_array($ids)) {
            $processedIds = [];
            foreach ($ids as $id => $attributes) {
                if (is_array($attributes)) {
                    $attributes = $this->addAuditFieldsToPivot($attributes, false);
                    $processedIds[$id] = $attributes;
                } else {
                    // If no attributes, $attributes is actually the ID
                    $processedIds[$attributes] = $this->addAuditFieldsToPivot([], true);
                }
            }
            $ids = $processedIds;
        }
        
        return parent::sync($ids, $detaching);
    }

    /**
     * Override the syncWithoutDetaching method
     */
    public function syncWithoutDetaching($ids)
    {
        return $this->sync($ids, false);
    }

    /**
     * Override the toggle method to automatically set audit fields
     */
    public function toggle($ids, $touch = true)
    {
        // Process ids similar to sync
        if (is_array($ids)) {
            $processedIds = [];
            foreach ($ids as $id => $attributes) {
                if (is_array($attributes)) {
                    $attributes = $this->addAuditFieldsToPivot($attributes, false);
                    $processedIds[$id] = $attributes;
                } else {
                    $processedIds[$attributes] = $this->addAuditFieldsToPivot([], true);
                }
            }
            $ids = $processedIds;
        }
        
        return parent::toggle($ids, $touch);
    }

    /**
     * Override the updateExistingPivot method to automatically set updated_by
     */
    public function updateExistingPivot($id, array $attributes, $touch = true)
    {
        // Add updated_by to the attributes
        $attributes = $this->addAuditFieldsToPivot($attributes, false);
        
        return parent::updateExistingPivot($id, $attributes, $touch);
    }

    /**
     * Add audit fields to pivot attributes
     * 
     * @param array $attributes
     * @param bool $isCreating Whether this is a create operation (sets created_by)
     * @return array
     */
    protected function addAuditFieldsToPivot(array $attributes, bool $isCreating = false): array
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Always set updated_by
            if (!isset($attributes['updated_by'])) {
                $attributes['updated_by'] = $userId;
            }
            
            // Set created_by only for new relationships
            if ($isCreating && !isset($attributes['created_by'])) {
                $attributes['created_by'] = $userId;
            }
        }
        
        return $attributes;
    }
} 