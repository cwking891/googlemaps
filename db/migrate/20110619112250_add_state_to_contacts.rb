class AddStateToContacts < ActiveRecord::Migration
  def self.up
    add_column :contacts, :state, :string
  end

  def self.down
    remove_column :contacts, :state
  end
end
